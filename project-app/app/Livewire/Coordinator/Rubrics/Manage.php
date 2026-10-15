<?php

namespace App\Livewire\Coordinator\Rubrics;

use App\Enums\RubricType;
use App\Models\Rubric;
use App\Models\RubricCriterion;
use App\Models\RubricVersion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Manage extends Component
{
    use WithPagination;

    public int $rubricId;

    public int $versionId;

    public array $criteria = [];

    public string $searchVersions = '';

    public function updatedSearchVersions(): void
    {
        foreach (Rubric::query()->pluck('id') as $rubricId) {
            $this->resetPage("rubricVersions{$rubricId}");
        }
    }

    public function mount(): void
    {
        $this->authorizeCoordinator();
        $rubric = Rubric::query()->where('type', RubricType::Supervisor)->firstOrFail();
        $this->selectVersion($rubric->versions()->firstOrFail());
    }

    public function select(int $rubricId, int $versionId): void
    {
        $this->authorizeCoordinator();
        $version = RubricVersion::query()
            ->where('rubric_id', $rubricId)->findOrFail($versionId);
        $this->selectVersion($version);
    }

    public function save(): void
    {
        $this->authorizeCoordinator();
        $version = $this->draft();
        $this->validate([
            'criteria' => ['array'],
            'criteria.*.name' => ['required', 'string', 'max:255'],
            'criteria.*.description' => ['nullable', 'string'],
            'criteria.*.weight' => ['required', 'numeric', 'min:0', 'max:100'],
            'criteria.*.scale' => ['array'],
            'criteria.*.scale.*.value' => ['nullable', 'numeric', 'min:0'],
            'criteria.*.scale.*.label' => ['nullable', 'string', 'max:255'],
        ]);
        DB::transaction(function () use ($version): void {
            foreach ($this->criteria as $index => $data) {
                $scale = array_values(array_filter($data['scale'] ?? [], fn ($level) => ($level['label'] ?? '') !== '' || ($level['value'] ?? '') !== ''));
                $version->criteria()->updateOrCreate(
                    ['id' => $data['id'] ?? null],
                    [
                        'name' => $data['name'],
                        'description' => $data['description'] ?? null,
                        'position' => $index + 1,
                        'weight' => $data['weight'],
                        'scale' => $scale,
                    ],
                );
            }
            $keep = collect($this->criteria)->pluck('id')->filter()->all();
            $version->criteria()->whereNotIn('id', $keep)->delete();
        });
        $this->loadCriteria($version);
        session()->flash('status', 'Borrador guardado.');
    }

    public function addCriterion(): void
    {
        $this->authorizeCoordinator();
        $this->draft();
        $this->criteria[] = ['name' => '', 'description' => '', 'weight' => 0, 'scale' => [
            ['value' => '', 'label' => ''],
            ['value' => '', 'label' => ''],
        ]];
    }

    public function removeCriterion(int $index): void
    {
        $this->authorizeCoordinator();
        $this->draft();
        unset($this->criteria[$index]);
        $this->criteria = array_values($this->criteria);
    }

    public function addScaleLevel(int $index): void
    {
        $this->authorizeCoordinator();
        $this->draft();
        $this->criteria[$index]['scale'][] = ['value' => '', 'label' => ''];
    }

    public function removeScaleLevel(int $criterionIndex, int $scaleIndex): void
    {
        $this->authorizeCoordinator();
        $this->draft();
        unset($this->criteria[$criterionIndex]['scale'][$scaleIndex]);
        $this->criteria[$criterionIndex]['scale'] = array_values($this->criteria[$criterionIndex]['scale']);
    }

    public function newDraft(int $rubricId): void
    {
        $this->authorizeCoordinator();
        $rubric = Rubric::query()->findOrFail($rubricId);
        DB::transaction(function () use ($rubric): void {
            $latest = $rubric->versions()->lockForUpdate()->firstOrFail();
            $draft = $rubric->versions()->create([
                'version' => $latest->version + 1,
                'status' => 'draft',
            ]);
            foreach ($latest->criteria as $criterion) {
                $draft->criteria()->create($criterion->only(['name', 'description', 'position', 'weight', 'scale']));
            }
        });
        $this->selectVersion($rubric->versions()->firstOrFail());
        session()->flash('status', 'Nuevo borrador creado.');
    }

    public function publish(): void
    {
        $this->authorizeCoordinator();
        $version = $this->draft();
        $this->save();
        $version->refresh()->publish();
        session()->flash('status', 'Versión publicada.');
    }

    public function render(): View
    {
        $this->authorizeCoordinator();

        $rubrics = Rubric::query()
            ->with('currentVersion')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();
        foreach ($rubrics as $rubric) {
            $rubric->setRelation('versions', $this->versionsFor($rubric));
        }

        return view('livewire.coordinator.rubrics.manage', [
            'rubrics' => $rubrics,
            'selectedVersion' => RubricVersion::query()->with('rubric')->findOrFail($this->versionId),
        ])->layout('layouts.app', ['title' => 'Rúbricas de evaluación']);
    }

    private function versionsFor(Rubric $rubric): LengthAwarePaginator
    {
        $query = $rubric->versions()->orderByDesc('updated_at')->orderByDesc('id');
        $search = trim($this->searchVersions);
        if ($search !== '') {
            $query->where(function ($query) use ($search): void {
                $status = match (mb_strtolower($search)) {
                    'publicada' => 'published',
                    'borrador' => 'draft',
                    default => $search,
                };
                $query
                    ->where('status', 'like', "%{$status}%")
                    ->orWhereHas('rubric', fn ($rubric) => $rubric->where('type', 'like', "%{$search}%"));
                if (ctype_digit($search)) {
                    $query->orWhere('version', (int) $search);
                }
            });
        }

        return $query->paginate(10, ['*'], "rubricVersions{$rubric->id}");
    }

    private function selectVersion(RubricVersion $version): void
    {
        $this->rubricId = $version->rubric_id;
        $this->versionId = $version->id;
        $this->loadCriteria($version);
    }

    private function loadCriteria(RubricVersion $version): void
    {
        $this->criteria = $version->criteria->map(fn (RubricCriterion $criterion) => [
            'id' => $criterion->id,
            'name' => $criterion->name,
            'description' => $criterion->description ?? '',
            'weight' => $criterion->weight,
            'scale' => array_map(fn ($level) => [
                'value' => $level['value'] ?? '',
                'label' => $level['label'] ?? '',
            ], $criterion->scale ?? []),
        ])->all();
    }

    private function draft(): RubricVersion
    {
        $version = RubricVersion::query()->where('rubric_id', $this->rubricId)->findOrFail($this->versionId);
        abort_if($version->isPublished(), 403, 'Las versiones publicadas son de solo lectura.');

        return $version;
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
