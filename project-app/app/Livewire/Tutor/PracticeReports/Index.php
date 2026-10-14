<?php

namespace App\Livewire\Tutor\PracticeReports;

use App\Enums\UserRole;
use App\Models\PracticeReportVersion;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        $this->authorizeTutor();

        return view('livewire.tutor.practice-reports.index', [
            'versions' => PracticeReportVersion::query()
                ->whereHas('report.practiceRequest', fn ($query) => $query->where('tutor_id', auth()->id()))
                ->with(['report.practiceRequest.student:id,name', 'report.practiceRequest.company:id,name'])
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Reportes finales']);
    }

    private function authorizeTutor(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Tutor, 403);
    }
}
