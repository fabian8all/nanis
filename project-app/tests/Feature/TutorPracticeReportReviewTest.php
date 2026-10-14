<?php

namespace Tests\Feature;

use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Livewire\Tutor\PracticeReports\Review;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TutorPracticeReportReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_section_observations_start_collapsed_and_keep_their_values_when_submitted(): void
    {
        $company = Company::query()->create(['name' => 'Empresa de prueba']);
        $student = User::factory()->create(['role' => UserRole::Student]);
        $tutor = User::factory()->create(['role' => UserRole::Tutor]);
        $request = $student->practiceRequests()->create([
            'tutor_id' => $tutor->id,
            'company_id' => $company->id,
            'ucol_email' => $student->email,
            'nss' => '00000000000',
            'account_number' => '00000000',
            'grade_group' => '8A',
            'start_date' => '2026-11-01',
            'practice_location' => 'Colima',
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'end_date' => '2027-01-31',
            'status' => PracticeRequestStatus::Approved,
            'non_responsiva_accepted_at' => now(),
        ]);
        $report = $request->practiceReport()->create([]);
        $version = $report->versions()->create([
            'version' => 1,
            'status' => PracticeReportStatus::PendingTutorReview,
            'company_name' => $company->name,
            'area' => 'Operaciones',
            'period' => 'Enero a marzo',
            'objectives' => 'Objetivo',
            'activities' => 'Actividades',
            'outcomes' => 'Resultados',
            'conclusions' => 'Conclusiones',
            'recommendations' => 'Recomendaciones',
            'submitted_at' => now(),
        ]);

        $this->actingAs($tutor);
        $review = Livewire::test(Review::class, ['practiceReportVersion' => $version])
            ->assertSee('Comentario general (obligatorio al devolver)')
            ->assertSee('Observación: Actividades realizadas');

        $html = $review->html();
        $this->assertSame(7, substr_count($html, '<details'));
        $this->assertStringNotContainsString('<details open', $html);

        $review
            ->set('reviewComment', 'Explica mejor el resultado general.')
            ->set('sectionComments.activities', 'Incluye fechas y responsables.')
            ->call('returnForChanges')
            ->assertHasNoErrors();

        $this->assertSame('Incluye fechas y responsables.', $version->fresh()->section_comments['activities']);
        $this->assertSame('Explica mejor el resultado general.', $version->fresh()->review_comment);
    }
}
