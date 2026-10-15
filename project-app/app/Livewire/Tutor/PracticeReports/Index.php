<?php

namespace App\Livewire\Tutor\PracticeReports;

use App\Enums\UserRole;
use App\Models\PracticeReportVersion;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $this->authorizeTutor();

        $query = PracticeReportVersion::query()
            ->whereHas('report.practiceRequest', fn ($query) => $query->where('tutor_id', auth()->id()))
            ->with(['report.practiceRequest.student:id,name', 'report.practiceRequest.company:id,name']);
        $search = trim($this->search);
        if ($search !== '') {
            $query->whereHas('report.practiceRequest', fn ($request) => $request
                ->whereHas('student', fn ($student) => $student->where('name', 'like', "%{$search}%"))
                ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$search}%")));
        }

        return view('livewire.tutor.practice-reports.index', [
            'versions' => $query
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->paginate(15),
        ])->layout('layouts.app', ['title' => 'Reportes finales']);
    }

    private function authorizeTutor(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Tutor, 403);
    }
}
