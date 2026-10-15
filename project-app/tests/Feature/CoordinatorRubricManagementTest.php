<?php

namespace Tests\Feature;

use App\Enums\RubricType;
use App\Enums\UserRole;
use App\Livewire\Coordinator\Rubrics\Manage;
use App\Models\Rubric;
use App\Models\RubricVersion;
use App\Models\User;
use App\Services\RubricScoreCalculator;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Tests\TestCase;

class CoordinatorRubricManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_seed_creates_two_independent_incomplete_rubric_drafts(): void
    {
        $supervisor = Rubric::query()->where('type', RubricType::Supervisor->value)->firstOrFail();
        $tutor = Rubric::query()->where('type', RubricType::Tutor->value)->firstOrFail();
        $this->assertSame(4, $supervisor->versions()->first()->criteria()->count());
        $this->assertSame([], $tutor->versions()->first()->criteria()->get()->all());
        $this->assertNull($supervisor->current_version_id);
        $this->assertNull($tutor->current_version_id);
        $this->assertSame(2, RubricVersion::query()->count());
    }

    public function test_database_constraints_reject_duplicate_types_versions_and_positions(): void
    {
        $rubric = Rubric::query()->where('type', RubricType::Supervisor->value)->firstOrFail();
        $version = $rubric->versions()->firstOrFail();
        try {
            Rubric::query()->create(['type' => RubricType::Supervisor]);
            $this->fail('Expected unique rubric type constraint.');
        } catch (UniqueConstraintViolationException) {
            $this->assertDatabaseCount('rubrics', 2);
        }
        try {
            $rubric->versions()->create(['version' => 1, 'status' => 'draft']);
            $this->fail('Expected unique rubric version constraint.');
        } catch (UniqueConstraintViolationException) {
            $this->assertDatabaseCount('rubric_versions', 2);
        }
        $position = (int) $version->criteria()->max('position') + 10;
        $version->criteria()->create(['name' => 'A', 'position' => $position, 'weight' => 0, 'scale' => []]);
        $this->expectException(UniqueConstraintViolationException::class);
        $version->criteria()->create(['name' => 'B', 'position' => $position, 'weight' => 0, 'scale' => []]);
    }

    public function test_coordinator_can_edit_validate_publish_and_create_next_type_scoped_version(): void
    {
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $this->actingAs($coordinator);
        $rubric = Rubric::query()->where('type', RubricType::Supervisor->value)->firstOrFail();
        $version = $rubric->versions()->firstOrFail();
        Livewire::test(Manage::class)
            ->call('publish')
            ->assertHasErrors()
            ->set('criteria.0.weight', 25)
            ->set('criteria.0.scale', [
                ['value' => 0, 'label' => 'Insuficiente'],
                ['value' => 1, 'label' => 'Suficiente'],
                ['value' => 2, 'label' => 'Excelente'],
            ])
            ->set('criteria.1.weight', 25)
            ->set('criteria.1.scale', [
                ['value' => 0, 'label' => 'Bajo'],
                ['value' => 1, 'label' => 'Medio'],
                ['value' => 2, 'label' => 'Alto'],
            ])
            ->set('criteria.2.weight', 25)
            ->set('criteria.2.scale', [
                ['value' => 0, 'label' => 'Bajo'],
                ['value' => 1, 'label' => 'Medio'],
                ['value' => 2, 'label' => 'Alto'],
            ])
            ->set('criteria.3.weight', 25)
            ->set('criteria.3.scale', [
                ['value' => 0, 'label' => 'Bajo'],
                ['value' => 1, 'label' => 'Medio'],
                ['value' => 2, 'label' => 'Alto'],
            ])
            ->call('publish')
            ->assertHasNoErrors();

        $this->assertSame($version->id, $rubric->fresh()->current_version_id);
        $this->assertSame('published', $version->fresh()->status);
        $published = $version->fresh();
        $this->expectException(\LogicException::class);
        $published->update(['version' => 9]);
    }

    public function test_user_without_coordinator_role_cannot_access_route_or_component_actions(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student]);
        $this->actingAs($student)->get(route('coordinacion.rubrics.manage'))->assertForbidden();
        Livewire::actingAs($student)->test(Manage::class)->assertForbidden();
    }

    public function test_coordinator_can_open_the_rubrics_management_route(): void
    {
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);

        $this->actingAs($coordinator)
            ->get(route('coordinacion.rubrics.manage'))
            ->assertOk()
            ->assertSee('Rúbricas de evaluación');
    }

    public function test_rubric_version_lists_can_be_searched_without_changing_the_selected_draft(): void
    {
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $this->actingAs($coordinator);
        $supervisorRubric = Rubric::query()->where('type', RubricType::Supervisor->value)->firstOrFail();
        $component = Livewire::test(Manage::class);
        $selectedVersionId = $component->get('versionId');
        $supervisorRubric->versions()->create(['version' => 2, 'status' => 'draft']);

        $component
            ->set('searchVersions', '2')
            ->assertSet('versionId', $selectedVersionId)
            ->assertViewHas('rubrics', fn ($rubrics) => array_sum(array_map(
                fn ($rubric) => $rubric->versions->total(),
                $rubrics->all(),
            )) === 1);
    }

    public function test_new_drafts_keep_version_sequences_and_criteria_separate_by_rubric_type(): void
    {
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $this->actingAs($coordinator);
        $supervisor = Rubric::query()->where('type', RubricType::Supervisor->value)->firstOrFail();
        $tutor = Rubric::query()->where('type', RubricType::Tutor->value)->firstOrFail();

        Livewire::test(Manage::class)
            ->call('newDraft', $supervisor->id)
            ->assertHasNoErrors();
        $supervisorDraft = $supervisor->versions()->firstOrFail();
        $this->assertSame(2, $supervisorDraft->version);
        $this->assertSame(4, $supervisorDraft->criteria()->count());

        Livewire::test(Manage::class)
            ->call('select', $tutor->id, $tutor->versions()->firstOrFail()->id)
            ->call('newDraft', $tutor->id)
            ->assertHasNoErrors();
        $this->assertSame(2, $tutor->versions()->firstOrFail()->version);
        $this->assertSame(0, $tutor->versions()->firstOrFail()->criteria()->count());
        $this->assertSame(2, $supervisor->versions()->count());
    }

    public function test_publishing_later_version_moves_only_the_matching_current_pointer(): void
    {
        $supervisor = Rubric::query()->where('type', RubricType::Supervisor->value)->firstOrFail();
        $tutor = Rubric::query()->where('type', RubricType::Tutor->value)->firstOrFail();
        $initialDraft = $supervisor->versions()->firstOrFail();
        $initialDraft->criteria()->update(['weight' => 25, 'scale' => [
            ['value' => 0, 'label' => 'No'],
            ['value' => 1, 'label' => 'Sí'],
        ]]);
        $initialDraft->publish();

        $next = $supervisor->versions()->create(['version' => 2, 'status' => 'draft']);
        $next->criteria()->createMany([
            ['name' => 'C', 'position' => 1, 'weight' => 100, 'scale' => [
                ['value' => 0, 'label' => 'No'],
                ['value' => 1, 'label' => 'Sí'],
            ]],
        ]);
        $next->publish();

        $this->assertSame($next->id, $supervisor->fresh()->current_version_id);
        $this->assertNull($tutor->fresh()->current_version_id);
        $this->assertSame('published', $initialDraft->fresh()->status);
        $this->assertSame(2, $supervisor->versions()->count());
    }

    public function test_published_version_is_immutable_and_calculator_uses_scale_maximum(): void
    {
        $rubric = Rubric::query()->where('type', RubricType::Tutor->value)->firstOrFail();
        $version = $rubric->versions()->firstOrFail();
        $version->criteria()->createMany([
            ['name' => 'A', 'position' => 1, 'weight' => 60, 'scale' => [
                ['value' => 0, 'label' => 'Bajo'],
                ['value' => 1, 'label' => 'Medio'],
                ['value' => 2, 'label' => 'Alto'],
            ]],
            ['name' => 'B', 'position' => 2, 'weight' => 40, 'scale' => [
                ['value' => 0, 'label' => 'No'],
                ['value' => 1, 'label' => 'Sí'],
            ]],
        ]);
        $version->publish();
        $criteria = $version->criteria;
        $score = app(RubricScoreCalculator::class)->calculate($version, [
            $criteria[0]->id => 1,
            $criteria[1]->id => 1,
        ]);
        $this->assertSame(70.0, $score);
        $this->assertSame($version->id, $rubric->fresh()->current_version_id);
        $this->expectException(\LogicException::class);
        $criteria[0]->update(['name' => 'Changed']);
    }
}
