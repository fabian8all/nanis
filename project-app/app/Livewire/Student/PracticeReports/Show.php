<?php

namespace App\Livewire\Student\PracticeReports;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\PracticeReport;
use App\Models\PracticeReportVersion;
use App\Models\PracticeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use RuntimeException;
use Throwable;

class Show extends Component
{
    use WithFileUploads;

    public int $practiceRequestId;

    public string $area = '';

    public string $period = '';

    public string $objectives = '';

    public string $activities = '';

    public string $outcomes = '';

    public string $conclusions = '';

    public string $recommendations = '';

    public array $attachments = [];

    public function mount(PracticeRequest $practiceRequest): void
    {
        $this->authorizeStudent();
        $this->practiceRequestId = $practiceRequest->id;
        $request = $this->eligibleRequest();
        $this->loadFields($request->practiceReport?->versions()->latest('version')->first());
    }

    public function saveDraft(): void
    {
        $this->authorizeStudent();
        $data = $this->validateFields();
        $version = $this->persistDraft($data);
        $this->storeAttachments($version);
        session()->flash('status', 'El borrador del reporte se guardó.');
    }

    public function submit(): void
    {
        $this->authorizeStudent();
        $data = $this->validateFields();
        $request = $this->eligibleRequest();
        if (! $request->tutor_id) {
            throw ValidationException::withMessages([
                'report' => 'Coordinación debe asignar un tutor antes de enviar el reporte.',
            ]);
        }

        $storedPaths = [];
        try {
            DB::transaction(function () use ($data, &$storedPaths): void {
                $request = PracticeRequest::query()->lockForUpdate()->findOrFail($this->practiceRequestId);
                $this->assertEligible($request);
                abort_unless($request->tutor_id, 422, 'Coordinación debe asignar un tutor antes de enviar el reporte.');

                [, $version] = $this->draftFor($request);
                $version->update($data);
                $this->storeAttachments($version, $storedPaths);
                $version->update([
                    'status' => PracticeReportStatus::PendingTutorReview,
                    'submitted_at' => now(),
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($storedPaths);
            throw $exception;
        }
        $this->attachments = [];
        session()->flash('status', 'El reporte se envió al tutor asignado.');
        $this->redirectRoute('alumno.practice-reports.show', ['practiceRequest' => $this->practiceRequestId]);
    }

    public function updatedAttachments(): void
    {
        $this->authorizeStudent();
        $this->validate([
            'attachments' => ['array'],
            'attachments.*' => ['file', 'max:20480', 'mimes:pdf,jpg,jpeg,png'],
        ], [
            'attachments.*.max' => 'Cada anexo debe pesar como máximo 20 MB.',
            'attachments.*.mimes' => 'Los anexos deben ser PDF, JPG o PNG.',
        ]);
    }

    public function render(): View
    {
        $this->authorizeStudent();
        $request = $this->eligibleRequest();
        $report = $request->practiceReport()
            ->with([
                'versions' => fn ($query) => $query
                    ->with(['attachments', 'reviewer:id,name'])
                    ->orderByDesc('updated_at')
                    ->orderByDesc('id'),
                'approvedVersion.attachments',
            ])
            ->first();
        $latest = $report?->versions->sortByDesc('version')->first();
        $editable = $latest && in_array($latest->status, [
            PracticeReportStatus::Draft,
            PracticeReportStatus::Returned,
        ], true);
        $approved = $report?->approvedVersion;

        return view('livewire.student.practice-reports.show', [
            'request' => $request,
            'report' => $report,
            'latest' => $latest,
            'approved' => $approved,
            'canEdit' => ! $latest || $editable,
        ])->layout('layouts.app', ['title' => 'Reporte final']);
    }

    private function validateFields(): array
    {
        return $this->validate([
            'area' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:255'],
            'objectives' => ['required', 'string', 'max:20000'],
            'activities' => ['required', 'string', 'max:20000'],
            'outcomes' => ['required', 'string', 'max:20000'],
            'conclusions' => ['required', 'string', 'max:20000'],
            'recommendations' => ['required', 'string', 'max:20000'],
            'attachments' => ['array'],
            'attachments.*' => ['file', 'max:20480', 'mimes:pdf,jpg,jpeg,png'],
        ]);
    }

    private function persistDraft(array $data): PracticeReportVersion
    {
        return DB::transaction(function () use ($data): PracticeReportVersion {
            $request = PracticeRequest::query()->lockForUpdate()->findOrFail($this->practiceRequestId);
            $this->assertEligible($request);
            [$report, $version] = $this->draftFor($request);
            $version->update($data);

            return $version;
        });
    }

    private function draftFor(PracticeRequest $request): array
    {
        $report = PracticeReport::query()
            ->where('practice_request_id', $request->id)
            ->lockForUpdate()
            ->first();
        if (! $report) {
            $report = $request->practiceReport()->create([]);
        }

        $latest = $report->versions()->lockForUpdate()->orderByDesc('version')->first();
        if ($latest?->status === PracticeReportStatus::Draft) {
            $version = $latest;
        } elseif ($latest?->status === PracticeReportStatus::Returned) {
            $version = $report->versions()->create([
                ...$latest->only([
                    'company_name', 'area', 'period', 'objectives', 'activities', 'outcomes', 'conclusions', 'recommendations',
                ]),
                'version' => $latest->version + 1,
                'status' => PracticeReportStatus::Draft,
            ]);
            foreach ($latest->attachments as $attachment) {
                $version->attachments()->create($attachment->only([
                    'file_path', 'original_name', 'mime_type', 'file_size',
                ]));
            }
        } elseif (! $latest) {
            $version = $report->versions()->create([
                'version' => 1,
                'status' => PracticeReportStatus::Draft,
                'company_name' => $request->company->name,
                'area' => '',
                'period' => '',
                'objectives' => '',
                'activities' => '',
                'outcomes' => '',
                'conclusions' => '',
                'recommendations' => '',
            ]);
        } else {
            throw ValidationException::withMessages([
                'report' => 'El reporte está cerrado. Coordinación debe autorizar su reapertura.',
            ]);
        }

        return [$report, $version];
    }

    private function storeAttachments(PracticeReportVersion $version, array &$storedPaths = []): void
    {
        foreach ($this->attachments as $file) {
            $extension = match ($file->getMimeType()) {
                'application/pdf' => 'pdf',
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                default => throw new RuntimeException('El tipo real del anexo no es compatible.'),
            };
            $path = $file->storeAs(
                "practice-reports/{$this->practiceRequestId}/{$version->version}",
                Str::uuid().'.'.$extension,
                'local',
            );
            if ($path === false) {
                throw new RuntimeException('No se pudo guardar el anexo en almacenamiento privado.');
            }
            $storedPaths[] = $path;
            $version->attachments()->create([
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }
        $this->attachments = [];
    }

    private function loadFields(?PracticeReportVersion $version): void
    {
        if (! $version) {
            return;
        }
        foreach (['area', 'period', 'objectives', 'activities', 'outcomes', 'conclusions', 'recommendations'] as $field) {
            $this->{$field} = $version->{$field};
        }
    }

    private function eligibleRequest(): PracticeRequest
    {
        $request = PracticeRequest::query()
            ->whereKey($this->practiceRequestId)
            ->where('user_id', auth()->id())
            ->where('status', PracticeRequestStatus::Approved->value)
            ->whereHas('practicePlan.versions', fn ($query) => $query
                ->where('status', PracticePlanStatus::Approved->value))
            ->whereHas('acceptanceSubmissions', fn ($query) => $query
                ->where('status', PracticeAcceptanceStatus::Accepted->value))
            ->with(['company:id,name', 'tutor:id,name'])
            ->firstOrFail();
        $this->assertEligible($request);

        return $request;
    }

    private function assertEligible(PracticeRequest $request): void
    {
        abort_unless(
            $request->user_id === auth()->id()
            && $request->status === PracticeRequestStatus::Approved
            && $request->practicePlan?->versions()
                ->where('status', PracticePlanStatus::Approved->value)
                ->exists()
            && $request->acceptanceSubmissions()
                ->where('status', PracticeAcceptanceStatus::Accepted->value)
                ->exists(),
            404,
        );
    }

    private function authorizeStudent(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Student, 403);
    }
}
