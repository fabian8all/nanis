<?php

namespace App\Livewire\Coordinator\PracticeSurveys;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeSurveyRequestStatus;
use App\Enums\PracticeSurveyVersionStatus;
use App\Models\PracticeRequest;
use App\Models\PracticeSurveyRequest;
use App\Models\PracticeSurveyVersion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Manage extends Component
{
    use WithPagination;

    public int $versionId;

    public array $questions = [];

    public string $versionsSearch = '';

    public string $eligibleSearch = '';

    public string $surveyRequestsSearch = '';

    public function updatedVersionsSearch(): void
    {
        $this->resetPage('versionsPage');
    }

    public function updatedEligibleSearch(): void
    {
        $this->resetPage('eligiblePage');
    }

    public function updatedSurveyRequestsSearch(): void
    {
        $this->resetPage('surveyRequestsPage');
    }

    public function mount(): void
    {
        $this->authorizeCoordinator();
        $version = PracticeSurveyVersion::query()
            ->where('status', PracticeSurveyVersionStatus::Draft)
            ->orderByDesc('version')
            ->first()
            ?? PracticeSurveyVersion::query()->orderByDesc('version')->first()
            ?? PracticeSurveyVersion::query()->create(['version' => 1, 'status' => PracticeSurveyVersionStatus::Draft]);
        $this->select($version);
    }

    public function selectVersion(int $versionId): void
    {
        $this->authorizeCoordinator();
        $this->select(PracticeSurveyVersion::query()->findOrFail($versionId));
    }

    public function addDraft(): void
    {
        $this->authorizeCoordinator();

        $draft = DB::transaction(function (): PracticeSurveyVersion {
            $latest = PracticeSurveyVersion::query()->orderByDesc('version')->lockForUpdate()->first();
            $existingDraft = PracticeSurveyVersion::query()
                ->where('status', PracticeSurveyVersionStatus::Draft)
                ->orderByDesc('version')
                ->first();
            if ($existingDraft) {
                return $existingDraft;
            }

            $version = PracticeSurveyVersion::query()->create([
                'version' => ($latest?->version ?? 0) + 1,
                'status' => PracticeSurveyVersionStatus::Draft,
            ]);
            $source = PracticeSurveyVersion::query()
                ->where('status', PracticeSurveyVersionStatus::Published)
                ->orderByDesc('version')
                ->first();
            if ($source) {
                foreach ($source->questions as $question) {
                    $version->questions()->create($question->only(['question', 'position', 'allow_comment']));
                }
            }

            return $version;
        });

        $this->select($draft);
        session()->flash('status', 'Borrador creado.');
    }

    public function addQuestion(): void
    {
        $this->authorizeCoordinator();
        $this->draft();
        $this->questions[] = ['question' => '', 'allow_comment' => false];
    }

    public function removeQuestion(int $index): void
    {
        $this->authorizeCoordinator();
        $this->draft();
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function saveQuestions(): void
    {
        $this->authorizeCoordinator();
        $version = $this->draft();
        $validated = $this->validate([
            'questions' => ['array'],
            'questions.*.question' => ['required', 'string', 'max:500'],
            'questions.*.allow_comment' => ['required', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $version): void {
            $locked = PracticeSurveyVersion::query()->lockForUpdate()->findOrFail($version->id);
            abort_if($locked->isPublished(), 403, 'Las versiones publicadas son de solo lectura.');
            $locked->questions()->delete();
            foreach ($validated['questions'] as $position => $question) {
                $locked->questions()->create([
                    'question' => $question['question'],
                    'position' => $position + 1,
                    'allow_comment' => $question['allow_comment'],
                ]);
            }
        });

        $this->select(PracticeSurveyVersion::query()->findOrFail($version->id));
        session()->flash('status', 'Borrador guardado.');
    }

    public function publish(): void
    {
        $this->authorizeCoordinator();
        $this->saveQuestions();
        $version = $this->draft();
        if ($version->questions()->count() === 0) {
            throw ValidationException::withMessages([
                'questions' => 'Agrega al menos una pregunta antes de publicar.',
            ]);
        }

        $version->publish();
        $this->select($version->fresh());
        session()->flash('status', 'Versión publicada.');
    }

    public function requestSurvey(int $practiceRequestId): void
    {
        $this->authorizeCoordinator();

        DB::transaction(function () use ($practiceRequestId): void {
            $practiceRequest = PracticeRequest::query()
                ->with('latestAcceptanceSubmission')
                ->lockForUpdate()
                ->findOrFail($practiceRequestId);
            $acceptance = $practiceRequest->latestAcceptanceSubmission;
            if ($acceptance?->status !== PracticeAcceptanceStatus::Accepted) {
                throw ValidationException::withMessages([
                    'request' => 'Solo se puede solicitar la encuesta para una práctica con aceptación vigente.',
                ]);
            }

            $publishedVersion = PracticeSurveyVersion::query()
                ->where('status', PracticeSurveyVersionStatus::Published)
                ->orderByDesc('version')
                ->first();
            if (! $publishedVersion) {
                throw ValidationException::withMessages([
                    'request' => 'Publica el cuestionario institucional antes de solicitar encuestas.',
                ]);
            }

            if ($practiceRequest->satisfactionSurveyRequest()->exists()) {
                throw ValidationException::withMessages([
                    'request' => 'Esta práctica ya tiene una solicitud de encuesta.',
                ]);
            }

            $practiceRequest->satisfactionSurveyRequest()->create([
                'practice_acceptance_submission_id' => $acceptance->id,
                'supervisor_id' => $acceptance->supervisor_id,
                'practice_survey_version_id' => $publishedVersion->id,
                'status' => PracticeSurveyRequestStatus::Requested,
                'requested_by' => auth()->id(),
                'requested_at' => now(),
            ]);
        });

        session()->flash('status', 'Encuesta solicitada al supervisor directo.');
    }

    public function cancelSurvey(int $surveyRequestId): void
    {
        $this->authorizeCoordinator();

        DB::transaction(function () use ($surveyRequestId): void {
            $surveyRequest = PracticeSurveyRequest::query()->lockForUpdate()->findOrFail($surveyRequestId);
            if ($surveyRequest->status !== PracticeSurveyRequestStatus::Requested) {
                throw ValidationException::withMessages([
                    'request' => 'Solo se pueden cancelar solicitudes de encuesta pendientes.',
                ]);
            }

            $surveyRequest->update([
                'status' => PracticeSurveyRequestStatus::Cancelled,
                'cancelled_by' => auth()->id(),
                'cancelled_at' => now(),
            ]);
        });
        session()->flash('status', 'Solicitud de encuesta cancelada.');
    }

    public function render(): View
    {
        $this->authorizeCoordinator();
        $selectedVersion = PracticeSurveyVersion::query()
            ->with('questions')
            ->findOrFail($this->versionId);
        $versionsQuery = PracticeSurveyVersion::query()
            ->with('questions')
            ->orderByDesc('updated_at')
            ->orderByDesc('id');
        $versionsSearch = trim($this->versionsSearch);
        if ($versionsSearch !== '') {
            $versionsQuery->where(function ($query) use ($versionsSearch): void {
                $query->where('status', 'like', "%{$versionsSearch}%");
                if (ctype_digit($versionsSearch)) {
                    $query->orWhere('version', (int) $versionsSearch);
                }
            });
        }

        $eligibleQuery = PracticeRequest::query()
            ->whereHas('latestAcceptanceSubmission', fn ($query) => $query
                ->where('status', PracticeAcceptanceStatus::Accepted))
            ->whereDoesntHave('satisfactionSurveyRequest')
            ->withMax('latestAcceptanceSubmission as acceptance_modified_at', 'updated_at')
            ->with(['student:id,name', 'company:id,name', 'latestAcceptanceSubmission.supervisor:id,name']);
        $eligibleSearch = trim($this->eligibleSearch);
        if ($eligibleSearch !== '') {
            $eligibleQuery->where(function ($query) use ($eligibleSearch): void {
                $query
                    ->whereHas('student', fn ($student) => $student->where('name', 'like', "%{$eligibleSearch}%"))
                    ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$eligibleSearch}%"))
                    ->orWhereHas('latestAcceptanceSubmission.supervisor', fn ($supervisor) => $supervisor
                        ->where('name', 'like', "%{$eligibleSearch}%"));
            });
        }

        $surveyRequestsQuery = PracticeSurveyRequest::query()
            ->with([
                'practiceRequest.student:id,name',
                'practiceRequest.company:id,name',
                'supervisor:id,name',
                'version',
                'answers.question',
            ])
            ->orderByDesc('updated_at')
            ->orderByDesc('id');
        $surveyRequestsSearch = trim($this->surveyRequestsSearch);
        if ($surveyRequestsSearch !== '') {
            $surveyRequestsQuery->where(function ($query) use ($surveyRequestsSearch): void {
                $query
                    ->whereHas('practiceRequest.student', fn ($student) => $student->where('name', 'like', "%{$surveyRequestsSearch}%"))
                    ->orWhereHas('practiceRequest.company', fn ($company) => $company->where('name', 'like', "%{$surveyRequestsSearch}%"))
                    ->orWhereHas('supervisor', fn ($supervisor) => $supervisor->where('name', 'like', "%{$surveyRequestsSearch}%"));
                $status = match (mb_strtolower($surveyRequestsSearch)) {
                    'solicitada' => PracticeSurveyRequestStatus::Requested->value,
                    'respondida' => PracticeSurveyRequestStatus::Responded->value,
                    'cancelada' => PracticeSurveyRequestStatus::Cancelled->value,
                    default => null,
                };
                if ($status !== null) {
                    $query->orWhere('status', $status);
                }
                if (ctype_digit($surveyRequestsSearch)) {
                    $query->orWhereHas('version', fn ($version) => $version->where('version', (int) $surveyRequestsSearch));
                }
            });
        }

        return view('livewire.coordinator.practice-surveys.manage', [
            'selectedVersion' => $selectedVersion,
            'versions' => $versionsQuery->paginate(10, ['*'], 'versionsPage'),
            'publishedVersion' => PracticeSurveyVersion::query()
                ->where('status', PracticeSurveyVersionStatus::Published)
                ->orderByDesc('version')
                ->first(),
            'eligiblePractices' => $eligibleQuery
                ->orderByDesc('acceptance_modified_at')
                ->orderByDesc('id')
                ->paginate(15, ['*'], 'eligiblePage'),
            'surveyRequests' => $surveyRequestsQuery
                ->paginate(15, ['*'], 'surveyRequestsPage'),
        ])->layout('layouts.app', ['title' => 'Encuestas de satisfacción']);
    }

    private function select(PracticeSurveyVersion $version): void
    {
        $this->versionId = $version->id;
        $this->questions = $version->questions->map(fn ($question) => [
            'question' => $question->question,
            'allow_comment' => $question->allow_comment,
        ])->all();
    }

    private function draft(): PracticeSurveyVersion
    {
        $version = PracticeSurveyVersion::query()->findOrFail($this->versionId);
        abort_if($version->isPublished(), 403, 'Las versiones publicadas son de solo lectura.');

        return $version;
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
