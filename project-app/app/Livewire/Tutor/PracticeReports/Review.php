<?php

namespace App\Livewire\Tutor\PracticeReports;

use App\Enums\PracticeReportStatus;
use App\Enums\UserRole;
use App\Models\PracticeReportVersion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Review extends Component
{
    public int $versionId;

    public string $reviewComment = '';

    public array $sectionComments = [];

    public function mount(PracticeReportVersion $practiceReportVersion): void
    {
        $this->versionId = $practiceReportVersion->id;
        $this->authorizeAssignedTutor();
    }

    public function approve(): void
    {
        $this->authorizeAssignedTutor();
        DB::transaction(function (): void {
            $version = PracticeReportVersion::query()->lockForUpdate()->findOrFail($this->versionId);
            $this->assertPending($version);
            $version->update([
                'status' => PracticeReportStatus::Approved,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'review_comment' => $this->reviewComment ?: null,
                'section_comments' => $this->validatedSectionComments(),
            ]);
            $version->report()->lockForUpdate()->firstOrFail()
                ->update(['approved_version_id' => $version->id]);
        });
        session()->flash('status', 'El reporte quedó aprobado.');
        $this->redirectRoute('tutor.practice-reports.index');
    }

    public function returnForChanges(): void
    {
        $this->authorizeAssignedTutor();
        $data = $this->validate([
            'reviewComment' => ['required', 'string', 'max:4000'],
            'sectionComments' => ['array'],
            'sectionComments.*' => ['nullable', 'string', 'max:2000'],
        ], [
            'reviewComment.required' => 'El comentario general es obligatorio para devolver el reporte.',
        ]);
        DB::transaction(function () use ($data): void {
            $version = PracticeReportVersion::query()->lockForUpdate()->findOrFail($this->versionId);
            $this->assertPending($version);
            $version->update([
                'status' => PracticeReportStatus::Returned,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'review_comment' => $data['reviewComment'],
                'section_comments' => $this->validatedSectionComments(),
            ]);
        });
        session()->flash('status', 'El reporte se devolvió al alumno.');
        $this->redirectRoute('tutor.practice-reports.index');
    }

    public function render(): View
    {
        $this->authorizeAssignedTutor();
        $version = PracticeReportVersion::query()
            ->with([
                'attachments',
                'reviewer:id,name',
                'report.practiceRequest.student:id,name',
                'report.practiceRequest.company:id,name',
                'report.practiceRequest.tutor:id,name',
                'report.versions.attachments',
            ])
            ->findOrFail($this->versionId);

        return view('livewire.tutor.practice-reports.review', [
            'version' => $version,
            'history' => $version->report->versions->sortBy('version'),
        ])->layout('layouts.app', ['title' => 'Revisar reporte final']);
    }

    private function validatedSectionComments(): array
    {
        $data = validator(['comments' => $this->sectionComments], [
            'comments' => ['array'],
            'comments.*' => ['nullable', 'string', 'max:2000'],
        ])->validate();

        return array_filter($data['comments'] ?? [], fn ($comment) => filled($comment));
    }

    private function assertPending(PracticeReportVersion $version): void
    {
        if ($version->status !== PracticeReportStatus::PendingTutorReview) {
            throw ValidationException::withMessages([
                'reviewComment' => 'Esta versión ya no está pendiente de revisión.',
            ]);
        }
    }

    private function authorizeAssignedTutor(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Tutor, 403);
        $assigned = PracticeReportVersion::query()
            ->whereKey($this->versionId)
            ->whereHas('report.practiceRequest', fn ($query) => $query->where('tutor_id', auth()->id()))
            ->exists();
        abort_unless($assigned, 404);
    }
}
