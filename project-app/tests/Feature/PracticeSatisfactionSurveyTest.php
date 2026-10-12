<?php

namespace Tests\Feature;

use App\Enums\PracticeSurveyRequestStatus;
use App\Enums\PracticeSurveyVersionStatus;
use App\Enums\UserRole;
use App\Livewire\Coordinator\PracticeSurveys\Manage;
use App\Livewire\Supervisor\PracticeSurveys\Answer;
use App\Models\Company;
use App\Models\PracticeSurveyAnswer;
use App\Models\PracticeSurveyRequest;
use App\Models\PracticeSurveyVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PracticeSatisfactionSurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_configures_and_publishes_an_immutable_versioned_questionnaire(): void
    {
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-surveys.manage'))
            ->assertOk()
            ->assertSee('escala de cada pregunta es de 1 a 5');

        Livewire::test(Manage::class)
            ->set('questions', [
                ['question' => '¿Cómo evalúa la comunicación?', 'allow_comment' => true],
                ['question' => '¿Recomendaría la experiencia?', 'allow_comment' => false],
            ])
            ->call('publish')
            ->assertHasNoErrors();

        $version = PracticeSurveyVersion::query()->sole();
        $this->assertSame(PracticeSurveyVersionStatus::Published, $version->status);
        $this->assertSame(2, $version->questions()->count());

        Livewire::test(Manage::class)
            ->call('addDraft')
            ->assertHasNoErrors()
            ->assertSet('questions.0.question', '¿Cómo evalúa la comunicación?');

        $this->expectException(\LogicException::class);
        $version->questions()->firstOrFail()->update(['question' => 'Pregunta alterada']);
    }

    public function test_request_targets_the_accepted_supervisor_and_keeps_its_question_version(): void
    {
        [$student, $coordinator, $supervisor, , $practiceRequest] = $this->createPractice();
        $this->actingAs($coordinator);
        $this->publishQuestionnaire([
            ['question' => '¿Cómo evalúa la experiencia?', 'allow_comment' => true],
        ]);

        Livewire::test(Manage::class)
            ->call('requestSurvey', $practiceRequest->id)
            ->assertHasNoErrors();

        $surveyRequest = PracticeSurveyRequest::query()->sole();
        $this->assertSame($practiceRequest->id, $surveyRequest->practice_request_id);
        $this->assertSame($supervisor->id, $surveyRequest->supervisor_id);
        $this->assertSame(PracticeSurveyRequestStatus::Requested, $surveyRequest->status);
        $originalVersionId = $surveyRequest->practice_survey_version_id;
        $this->assertSame($student->id, $surveyRequest->practiceRequest->user_id);

        $manage = Livewire::test(Manage::class)->call('addDraft');
        $manage->set('questions', [
            ['question' => 'Pregunta nueva de otra versión', 'allow_comment' => false],
        ])->call('publish')->assertHasNoErrors();

        $this->assertSame($originalVersionId, $surveyRequest->fresh()->practice_survey_version_id);
        $this->assertSame(1, PracticeSurveyRequest::query()->count());
        $this->assertDatabaseHas('practice_survey_versions', [
            'id' => $originalVersionId,
            'status' => PracticeSurveyVersionStatus::Published->value,
        ]);
    }

    public function test_assigned_supervisor_can_review_only_their_own_final_response_and_coordinator_can_review_all(): void
    {
        [, $coordinator, $supervisor, $otherSupervisor, $practiceRequest] = $this->createPractice();
        $this->actingAs($coordinator);
        $this->publishQuestionnaire([
            ['question' => '¿Cómo evalúa la comunicación?', 'allow_comment' => true],
            ['question' => '¿Recomendaría la empresa?', 'allow_comment' => false],
        ]);
        Livewire::test(Manage::class)->call('requestSurvey', $practiceRequest->id)->assertHasNoErrors();
        $surveyRequest = PracticeSurveyRequest::query()->sole();
        $questions = $surveyRequest->version->questions;

        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-surveys.index'))
            ->assertOk()
            ->assertSee($practiceRequest->company->name)
            ->assertSee(route('supervisor.practice-surveys.answer', $surveyRequest));
        $this->get(route('supervisor.practice-surveys.answer', $surveyRequest))->assertOk();

        $this->actingAs($otherSupervisor)
            ->get(route('supervisor.practice-surveys.answer', $surveyRequest))
            ->assertNotFound();
        $this->actingAs(User::factory()->create(['role' => UserRole::Tutor]))
            ->get(route('coordinacion.practice-surveys.manage'))
            ->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => UserRole::Student]))
            ->get(route('coordinacion.practice-surveys.manage'))
            ->assertForbidden();

        $this->actingAs($supervisor);
        Livewire::test(Answer::class, ['practiceSurveyRequest' => $surveyRequest])
            ->set("answers.{$questions[0]->id}.rating", 6)
            ->set("answers.{$questions[1]->id}.rating", 3)
            ->call('submit')
            ->assertHasErrors(["answers.{$questions[0]->id}.rating"]);

        Livewire::test(Answer::class, ['practiceSurveyRequest' => $surveyRequest])
            ->set("answers.{$questions[0]->id}.rating", 5)
            ->set("answers.{$questions[0]->id}.comment", 'Atención oportuna')
            ->set("answers.{$questions[1]->id}.rating", 3)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('supervisor.practice-surveys.index'));

        $surveyRequest->refresh();
        $this->assertSame(PracticeSurveyRequestStatus::Responded, $surveyRequest->status);
        $this->assertNotNull($surveyRequest->responded_at);
        $this->assertDatabaseHas('practice_survey_answers', [
            'practice_survey_request_id' => $surveyRequest->id,
            'practice_survey_question_id' => $questions[0]->id,
            'rating' => 5,
            'comment' => 'Atención oportuna',
        ]);
        $this->assertDatabaseCount('practice_survey_answers', 2);

        $this->get(route('supervisor.practice-surveys.answer', $surveyRequest))
            ->assertOk()
            ->assertSee('Respuesta final e inmutable')
            ->assertSee('Atención oportuna')
            ->assertDontSee('Enviar respuesta final');
        $this->get(route('supervisor.practice-surveys.index'))
            ->assertOk()
            ->assertSee('Consultar mi respuesta');

        Livewire::test(Answer::class, ['practiceSurveyRequest' => $surveyRequest])
            ->set("answers.{$questions[0]->id}.rating", 1)
            ->call('submit')
            ->assertHasErrors('survey');
        $this->assertDatabaseHas('practice_survey_answers', [
            'practice_survey_request_id' => $surveyRequest->id,
            'practice_survey_question_id' => $questions[0]->id,
            'rating' => 5,
        ]);

        $this->actingAs($otherSupervisor)
            ->get(route('supervisor.practice-surveys.answer', $surveyRequest))
            ->assertNotFound();

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-surveys.manage'))
            ->assertOk()
            ->assertSee('Atención oportuna')
            ->assertSee('5 / 5')
            ->assertSee('3 / 5');

        $answer = PracticeSurveyAnswer::query()->firstOrFail();
        $this->expectException(\LogicException::class);
        $answer->update(['rating' => 1]);
    }

    public function test_coordinator_can_cancel_a_pending_request_but_it_cannot_be_reopened_or_answered(): void
    {
        [, $coordinator, $supervisor, , $practiceRequest] = $this->createPractice();
        $this->actingAs($coordinator);
        $this->publishQuestionnaire([
            ['question' => '¿Cómo evalúa la experiencia?', 'allow_comment' => false],
        ]);
        Livewire::test(Manage::class)->call('requestSurvey', $practiceRequest->id)->assertHasNoErrors();
        $surveyRequest = PracticeSurveyRequest::query()->sole();

        Livewire::test(Manage::class)
            ->call('cancelSurvey', $surveyRequest->id)
            ->assertHasNoErrors();
        $this->assertSame(PracticeSurveyRequestStatus::Cancelled, $surveyRequest->fresh()->status);
        $this->assertNotNull($surveyRequest->fresh()->cancelled_at);
        $this->assertSame($coordinator->id, $surveyRequest->fresh()->cancelled_by);

        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-surveys.answer', $surveyRequest))
            ->assertNotFound();

        $this->actingAs($coordinator);
        Livewire::test(Manage::class)
            ->call('requestSurvey', $practiceRequest->id)
            ->assertHasErrors('request');
    }

    public function test_request_cannot_be_sent_without_published_questions_or_an_accepted_supervisor(): void
    {
        [, $coordinator, , , $practiceRequest] = $this->createPractice(withAcceptedSubmission: false);
        $this->actingAs($coordinator);

        Livewire::test(Manage::class)
            ->call('requestSurvey', $practiceRequest->id)
            ->assertHasErrors('request');
        $this->assertDatabaseCount('practice_survey_requests', 0);

        Livewire::test(Manage::class)
            ->set('questions', [])
            ->call('publish')
            ->assertHasErrors('questions');
        $this->assertSame(PracticeSurveyVersionStatus::Draft, PracticeSurveyVersion::query()->sole()->status);
    }

    private function publishQuestionnaire(array $questions): void
    {
        Livewire::test(Manage::class)
            ->set('questions', $questions)
            ->call('publish')
            ->assertHasNoErrors();
    }

    private function createPractice(bool $withAcceptedSubmission = true): array
    {
        $company = Company::query()->create(['name' => 'Hotel de prueba']);
        $student = User::factory()->create(['role' => UserRole::Student]);
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $company->id,
            'email_verified_at' => now(),
        ]);
        $otherSupervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => Company::query()->create(['name' => 'Otra empresa'])->id,
            'email_verified_at' => now(),
        ]);
        $practiceRequest = $student->practiceRequests()->create([
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
            'status' => 'approved',
            'non_responsiva_accepted_at' => now(),
        ]);
        if ($withAcceptedSubmission) {
            $practiceRequest->acceptanceSubmissions()->create([
                'supervisor_id' => $supervisor->id,
                'version' => 1,
                'file_path' => "acceptances/{$practiceRequest->id}.pdf",
                'file_extension' => 'pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 10,
                'status' => 'accepted',
                'submitted_at' => now(),
            ]);
        }

        return [$student, $coordinator, $supervisor, $otherSupervisor, $practiceRequest];
    }
}
