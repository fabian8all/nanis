<?php

namespace App\Livewire\Student\PracticePlans;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\PracticePlan;
use App\Models\PracticeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public int $practiceRequestId;

    public array $activities = [];

    public function mount(PracticeRequest $practiceRequest): void
    {
        $this->authorizeStudent();
        $this->practiceRequestId = $practiceRequest->id;
        $this->eligibleRequest();
        $this->loadLatestActivities();
    }

    public function addActivity(): void
    {
        $this->authorizeStudent();
        abort_unless($this->canEdit(), 403);
        $this->activities[] = $this->emptyActivity();
    }

    public function removeActivity(int $index): void
    {
        $this->authorizeStudent();
        abort_unless($this->canEdit(), 403);
        unset($this->activities[$index]);
        $this->activities = array_values($this->activities);
    }

    public function submit(): void
    {
        $this->authorizeStudent();
        abort_unless($this->canEdit(), 403);

        $data = $this->validate([
            'activities' => ['required', 'array', 'min:1', 'max:100'],
            'activities.*.description' => ['required', 'string', 'max:5000'],
            'activities.*.responsible' => ['required', 'string', 'max:255'],
            'activities.*.start_date' => ['required', 'date'],
            'activities.*.end_date' => ['required', 'date', 'after_or_equal:activities.*.start_date'],
            'activities.*.expected_result' => ['required', 'string', 'max:5000'],
        ], [
            'activities.*.end_date.after_or_equal' => 'La fecha de término no puede ser anterior al inicio.',
        ]);

        DB::transaction(function () use ($data): void {
            $request = PracticeRequest::query()
                ->lockForUpdate()
                ->findOrFail($this->practiceRequestId);
            $this->assertEligibleRequest($request);

            $plan = PracticePlan::query()
                ->where('practice_request_id', $request->id)
                ->lockForUpdate()
                ->first();
            if (! $plan) {
                $plan = $request->practicePlan()->create([]);
            }

            $latest = $plan->versions()->lockForUpdate()->orderByDesc('version')->first();
            if (
                $latest
                && ! in_array($latest->status, [
                    PracticePlanStatus::ReturnedBySupervisor,
                    PracticePlanStatus::ReturnedByCoordinator,
                ], true)
            ) {
                throw ValidationException::withMessages([
                    'activities' => 'El plan no está disponible para cambios.',
                ]);
            }

            $version = $plan->versions()->create([
                'version' => (int) $plan->versions()->max('version') + 1,
                'status' => PracticePlanStatus::PendingSupervisorReview,
                'submitted_at' => now(),
            ]);
            foreach (array_values($data['activities']) as $index => $activity) {
                $version->activities()->create([
                    'position' => $index + 1,
                    ...$activity,
                ]);
            }
        });

        session()->flash('status', 'Tu plan de trabajo se envió al supervisor de la empresa.');
        $this->redirectRoute('alumno.practice-plans.show', [
            'practiceRequest' => $this->practiceRequestId,
        ]);
    }

    public function render(): View
    {
        $this->authorizeStudent();
        $request = $this->eligibleRequest();
        $plan = PracticePlan::query()
            ->where('practice_request_id', $request->id)
            ->with([
                'versions' => fn ($query) => $query
                    ->with([
                        'activities',
                        'supervisorReviewer:id,name',
                        'coordinatorReviewer:id,name',
                    ])
                    ->orderByDesc('updated_at')
                    ->orderByDesc('id'),
            ])
            ->first();
        $latestVersion = $plan?->versions->sortByDesc('version')->first();

        return view('livewire.student.practice-plans.show', [
            'request' => $request,
            'plan' => $plan,
            'latestVersion' => $latestVersion,
        ])->layout('layouts.app', ['title' => 'Plan de trabajo']);
    }

    private function loadLatestActivities(): void
    {
        $request = $this->eligibleRequest();
        $latest = $request->practicePlan()
            ->with('latestVersion.activities')
            ->first()
            ?->latestVersion;

        if ($latest && in_array($latest->status, [
            PracticePlanStatus::ReturnedBySupervisor,
            PracticePlanStatus::ReturnedByCoordinator,
        ], true)) {
            $this->activities = $latest->activities
                ->map(fn ($activity): array => [
                    'description' => $activity->description,
                    'responsible' => $activity->responsible,
                    'start_date' => $activity->start_date->toDateString(),
                    'end_date' => $activity->end_date->toDateString(),
                    'expected_result' => $activity->expected_result,
                ])
                ->all();
        } else {
            $this->activities = [$this->emptyActivity()];
        }
    }

    private function canEdit(): bool
    {
        $request = $this->eligibleRequest();
        $latest = $request->practicePlan()
            ->with('latestVersion')
            ->first()
            ?->latestVersion;

        return ! $latest || in_array($latest->status, [
            PracticePlanStatus::ReturnedBySupervisor,
            PracticePlanStatus::ReturnedByCoordinator,
        ], true);
    }

    private function eligibleRequest(): PracticeRequest
    {
        $request = PracticeRequest::query()
            ->whereKey($this->practiceRequestId)
            ->where('user_id', auth()->id())
            ->where('status', PracticeRequestStatus::Approved->value)
            ->whereHas('acceptanceSubmissions', fn ($query) => $query
                ->where('status', PracticeAcceptanceStatus::Accepted->value))
            ->with(['student:id,name', 'company:id,name'])
            ->firstOrFail();

        return $request;
    }

    private function assertEligibleRequest(PracticeRequest $request): void
    {
        abort_unless(
            $request->user_id === auth()->id()
            && $request->status === PracticeRequestStatus::Approved
            && $request->acceptanceSubmissions()
                ->where('status', PracticeAcceptanceStatus::Accepted->value)
                ->exists(),
            404,
        );
    }

    private function emptyActivity(): array
    {
        return [
            'description' => '',
            'responsible' => '',
            'start_date' => '',
            'end_date' => '',
            'expected_result' => '',
        ];
    }

    private function authorizeStudent(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Student, 403);
    }
}
