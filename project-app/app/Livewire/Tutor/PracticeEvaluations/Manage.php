<?php

namespace App\Livewire\Tutor\PracticeEvaluations;

use App\Enums\PracticeEvaluationStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\RubricType;
use App\Enums\UserRole;
use App\Models\PracticeEvaluation;
use App\Models\PracticeEvaluationCriterion;
use App\Models\PracticeReportVersion;
use App\Models\PracticeRequest;
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
        $this->authorizeAssignedTutor();
        $evaluation = $practiceRequest->practiceEvaluation;
        if ($evaluation) {
            $this->loadScores($evaluation);
        }
    }

    public function startEvaluation(): void
    {
        $this->authorizeAssignedTutor();
        DB::transaction(function (): void {
            $request = PracticeRequest::query()->lockForUpdate()->findOrFail($this->practiceRequestId);
            $this->assertAssignedTutor($request);
            if ($request->practiceEvaluation()->exists()) {
                return;
            }
            $reportVersion = $this->approvedReportVersion($request);
            $rubricVersion = Rubric::query()
                ->where('type', RubricType::Tutor->value)
                ->with('currentVersion.criteria')
                ->first()?->currentVersion;
            if (! $rubricVersion || ! $rubricVersion->isPublished()) {
                throw ValidationException::withMessages([
                    'evaluation' => 'Coordinación debe completar y publicar la rúbrica del tutor antes de iniciar una evaluación.',
                ]);
            }

            $calculator = app(RubricScoreCalculator::class);
            $calculator->validateVersion($rubricVersion);
            $evaluation = $request->practiceEvaluation()->create([
                'tutor_id' => auth()->id(),
                'practice_report_version_id' => $reportVersion->id,
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
        $this->loadScores(PracticeEvaluation::query()
            ->where('practice_request_id', $this->practiceRequestId)
            ->firstOrFail());
    }

    public function saveDraft(): void
    {
        $this->authorizeAssignedTutor();
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
        $this->authorizeAssignedTutor();
        $data = $this->validate(['scores' => ['array'], 'scores.*' => ['nullable', 'numeric']]);
        DB::transaction(function () use ($data): void {
            $evaluation = $this->lockDraft();
            $scoreValues = [];
            foreach ($evaluation->criteria as $criterion) {
                $value = $data['scores'][$criterion->id] ?? null;
                $this->assertValidScore($criterion, $value, false);
                $scoreValues[$criterion->rubric_criterion_id] = (float) $value;
            }

            $rubricVersion = RubricVersion::query()->findOrFail($evaluation->rubric_version_id);
            $total = app(RubricScoreCalculator::class)->calculate($rubricVersion, $scoreValues);
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
        $this->redirectRoute('tutor.practice-evaluations.show', [
            'practiceEvaluation' => PracticeEvaluation::query()
                ->where('practice_request_id', $this->practiceRequestId)
                ->firstOrFail(),
        ]);
    }

    public function render(): View
    {
        $this->authorizeAssignedTutor();
        $request = PracticeRequest::query()
            ->with(['student:id,name', 'company:id,name', 'practiceReport.approvedVersion'])
            ->findOrFail($this->practiceRequestId);
        $evaluation = $request->practiceEvaluation()
            ->with(['criteria', 'reportVersion', 'rubricVersion'])
            ->first();

        return view('livewire.tutor.practice-evaluations.manage', [
            'request' => $request,
            'evaluation' => $evaluation,
        ])->layout('layouts.app', ['title' => 'Evaluación final del tutor']);
    }

    private function approvedReportVersion(PracticeRequest $request): PracticeReportVersion
    {
        $version = $request->practiceReport?->approvedVersion;
        $isCurrent = $version && (int) $request->practiceReport->versions()->max('version') === $version->version;
        if (
            ! $version
            || ! $isCurrent
            || $version->status !== PracticeReportStatus::Approved
            || $version->reviewed_by !== auth()->id()
        ) {
            throw ValidationException::withMessages([
                'evaluation' => 'Solo el tutor que aprobó el reporte vigente puede iniciar esta evaluación.',
            ]);
        }

        return $version;
    }

    private function lockDraft(): PracticeEvaluation
    {
        $evaluation = PracticeEvaluation::query()
            ->where('practice_request_id', $this->practiceRequestId)
            ->lockForUpdate()
            ->with('criteria')
            ->firstOrFail();
        if ($evaluation->status !== PracticeEvaluationStatus::Draft || $evaluation->tutor_id !== auth()->id()) {
            throw ValidationException::withMessages([
                'evaluation' => 'La evaluación ya fue enviada o no está disponible para edición.',
            ]);
        }

        return $evaluation;
    }

    private function assertValidScore(PracticeEvaluationCriterion $criterion, mixed $value, bool $nullable): void
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

    private function labelFor(PracticeEvaluationCriterion $criterion, float $score): string
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

    private function loadScores(PracticeEvaluation $evaluation): void
    {
        foreach ($evaluation->criteria as $criterion) {
            $this->scores[$criterion->id] = $criterion->score === null ? '' : (string) $criterion->score;
        }
    }

    private function authorizeAssignedTutor(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Tutor, 403);
        $request = PracticeRequest::query()->findOrFail($this->practiceRequestId);
        $this->assertAssignedTutor($request);
    }

    private function assertAssignedTutor(PracticeRequest $request): void
    {
        abort_unless($request->tutor_id === auth()->id(), 404);
    }
}
