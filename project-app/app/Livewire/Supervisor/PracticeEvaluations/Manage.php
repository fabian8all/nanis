<?php

namespace App\Livewire\Supervisor\PracticeEvaluations;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeEvaluationStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\RubricType;
use App\Enums\UserRole;
use App\Models\PracticeRequest;
use App\Models\PracticeSupervisorEvaluation;
use App\Models\PracticeSupervisorEvaluationCriterion;
use App\Models\Rubric;
use App\Models\RubricVersion;
use App\Services\RubricScoreCalculator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Manage extends Component
{
    public int $practiceRequestId;

    public array $scores = [];

    public function mount(PracticeRequest $practiceRequest): void
    {
        $this->practiceRequestId = $practiceRequest->id;
        $request = $this->eligibleRequest();
        if ($evaluation = $request->practiceSupervisorEvaluation) {
            $this->loadScores($evaluation);
        }
    }

    public function startEvaluation(): void
    {
        $this->authorizeSupervisor();
        DB::transaction(function (): void {
            $request = PracticeRequest::query()->lockForUpdate()->findOrFail($this->practiceRequestId);
            $this->assertEligibleRequest($request);
            if ($request->practiceSupervisorEvaluation()->exists()) {
                return;
            }

            $acceptance = $request->latestAcceptanceSubmission()->lockForUpdate()->firstOrFail();
            $rubricVersion = Rubric::query()
                ->where('type', RubricType::Supervisor->value)
                ->with('currentVersion.criteria')
                ->first()?->currentVersion;
            if (! $rubricVersion || ! $rubricVersion->isPublished()) {
                throw ValidationException::withMessages([
                    'evaluation' => 'Coordinación debe completar y publicar la rúbrica del supervisor antes de iniciar una evaluación.',
                ]);
            }

            app(RubricScoreCalculator::class)->validateVersion($rubricVersion);
            $evaluation = $request->practiceSupervisorEvaluation()->create([
                'practice_acceptance_submission_id' => $acceptance->id,
                'supervisor_id' => auth()->id(),
                'rubric_version_id' => $rubricVersion->id,
                'status' => PracticeEvaluationStatus::Draft,
            ]);
            foreach ($rubricVersion->criteria as $criterion) {
                $evaluation->criteria()->create([
                    'rubric_criterion_id' => $criterion->id,
                    'name' => $criterion->name,
                    'description' => $criterion->description,
                    'position' => $criterion->position,
                    'weight' => $criterion->weight,
                    'scale' => $criterion->scale,
                ]);
            }
        });
        $this->loadScores(PracticeSupervisorEvaluation::query()
            ->where('practice_request_id', $this->practiceRequestId)
            ->firstOrFail());
    }

    public function saveDraft(): void
    {
        $this->authorizeSupervisor();
        $data = $this->validate(['scores' => ['array'], 'scores.*' => ['nullable', 'numeric']]);
        DB::transaction(function () use ($data): void {
            $evaluation = $this->lockDraft();
            foreach ($evaluation->criteria as $criterion) {
                $value = $data['scores'][$criterion->id] ?? null;
                $this->assertValidScore($criterion, $value, true);
                $criterion->update([
                    'score' => $value,
                    'score_label' => $value === null ? null : $this->labelFor($criterion, (float) $value),
                ]);
            }
        });
        session()->flash('status', 'El borrador de la evaluación se guardó.');
    }

    public function submit(): void
    {
        $this->authorizeSupervisor();
        $data = $this->validate(['scores' => ['array'], 'scores.*' => ['nullable', 'numeric']]);
        DB::transaction(function () use ($data): void {
            $evaluation = $this->lockDraft();
            $scoreValues = [];
            foreach ($evaluation->criteria as $criterion) {
                $value = $data['scores'][$criterion->id] ?? null;
                $this->assertValidScore($criterion, $value, false);
                $scoreValues[$criterion->rubric_criterion_id] = (float) $value;
            }

            $version = RubricVersion::query()->findOrFail($evaluation->rubric_version_id);
            $total = app(RubricScoreCalculator::class)->calculate($version, $scoreValues);
            foreach ($evaluation->criteria as $criterion) {
                $score = (float) $scoreValues[$criterion->rubric_criterion_id];
                $maximum = max(array_column($criterion->scale, 'value'));
                $criterion->update([
                    'score' => $score,
                    'score_label' => $this->labelFor($criterion, $score),
                    'contribution' => round(($score / $maximum) * $criterion->weight, 2),
                ]);
            }
            $evaluation->update([
                'status' => PracticeEvaluationStatus::Submitted,
                'score_total' => $total,
                'submitted_at' => now(),
            ]);
        });
        session()->flash('status', 'La evaluación se envió y quedó cerrada.');
        $this->redirectRoute('supervisor.practice-supervisor-evaluations.show', [
            'practiceSupervisorEvaluation' => PracticeSupervisorEvaluation::query()
                ->where('practice_request_id', $this->practiceRequestId)
                ->firstOrFail(),
        ]);
    }

    public function render(): View
    {
        $this->authorizeSupervisor();
        $request = $this->eligibleRequest();
        $evaluation = $request->practiceSupervisorEvaluation()
            ->with(['criteria', 'rubricVersion', 'acceptanceSubmission'])
            ->first();

        return view('livewire.supervisor.practice-evaluations.manage', [
            'request' => $request,
            'evaluation' => $evaluation,
        ])->layout('layouts.app', ['title' => 'Evaluación final del supervisor']);
    }

    private function eligibleRequest(): PracticeRequest
    {
        $this->authorizeSupervisor();
        $request = PracticeRequest::query()
            ->whereKey($this->practiceRequestId)
            ->where('status', PracticeRequestStatus::Approved->value)
            ->whereHas('latestAcceptanceSubmission', fn ($query) => $query
                ->where('supervisor_id', auth()->id())
                ->where('status', PracticeAcceptanceStatus::Accepted->value))
            ->whereHas('practicePlan.latestVersion', fn ($query) => $query
                ->where('status', PracticePlanStatus::Approved->value))
            ->with([
                'student:id,name',
                'company:id,name',
                'latestAcceptanceSubmission',
                'practicePlan.latestVersion',
            ])
            ->firstOrFail();

        return $request;
    }

    private function assertEligibleRequest(PracticeRequest $request): void
    {
        abort_unless(
            $request->status === PracticeRequestStatus::Approved
            && $request->latestAcceptanceSubmission()
                ->where('supervisor_id', auth()->id())
                ->where('status', PracticeAcceptanceStatus::Accepted->value)
                ->exists()
            && $request->practicePlan()
                ->whereHas('latestVersion', fn ($query) => $query
                    ->where('status', PracticePlanStatus::Approved->value))
                ->exists(),
            404,
        );
    }

    private function lockDraft(): PracticeSupervisorEvaluation
    {
        $evaluation = PracticeSupervisorEvaluation::query()
            ->where('practice_request_id', $this->practiceRequestId)
            ->lockForUpdate()
            ->with('criteria')
            ->firstOrFail();
        if (
            $evaluation->status !== PracticeEvaluationStatus::Draft
            || $evaluation->supervisor_id !== auth()->id()
        ) {
            throw ValidationException::withMessages([
                'evaluation' => 'La evaluación ya fue enviada o no está disponible para edición.',
            ]);
        }

        return $evaluation;
    }

    private function assertValidScore(PracticeSupervisorEvaluationCriterion $criterion, mixed $value, bool $nullable): void
    {
        if ($value === null && $nullable) {
            return;
        }
        $allowed = array_map('floatval', array_column($criterion->scale, 'value'));
        if (! is_numeric($value) || ! in_array((float) $value, $allowed, true)) {
            throw ValidationException::withMessages([
                "scores.{$criterion->id}" => 'Selecciona un nivel válido de la escala.',
            ]);
        }
    }

    private function labelFor(PracticeSupervisorEvaluationCriterion $criterion, float $score): string
    {
        foreach ($criterion->scale as $level) {
            if ((float) $level['value'] === $score) {
                return $level['label'];
            }
        }
        throw ValidationException::withMessages([
            "scores.{$criterion->id}" => 'Selecciona un nivel válido de la escala.',
        ]);
    }

    private function loadScores(PracticeSupervisorEvaluation $evaluation): void
    {
        foreach ($evaluation->criteria as $criterion) {
            $this->scores[$criterion->id] = $criterion->score === null ? '' : (string) $criterion->score;
        }
    }

    private function authorizeSupervisor(): void
    {
        $user = auth()->user();
        abort_unless(
            $user?->role === UserRole::Supervisor
            && $user->isSupervisor()
            && $user->email_verified_at !== null,
            403,
        );
    }
}
