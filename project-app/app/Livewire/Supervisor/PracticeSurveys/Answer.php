<?php

namespace App\Livewire\Supervisor\PracticeSurveys;

use App\Enums\PracticeSurveyRequestStatus;
use App\Enums\UserRole;
use App\Models\PracticeSurveyRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Answer extends Component
{
    public int $surveyRequestId;

    public array $answers = [];

    public function mount(PracticeSurveyRequest $practiceSurveyRequest): void
    {
        $this->surveyRequestId = $practiceSurveyRequest->id;
        $surveyRequest = $this->authorizedRequest();
        foreach ($surveyRequest->version->questions as $question) {
            $this->answers[$question->id] = ['rating' => '', 'comment' => ''];
        }
    }

    public function submit(): void
    {
        $surveyRequest = $this->authorizedRequest();
        $questions = $surveyRequest->version->questions;
        $rules = ['answers' => ['required', 'array']];
        foreach ($questions as $question) {
            $rules["answers.{$question->id}.rating"] = ['required', 'integer', 'between:1,5'];
            $rules["answers.{$question->id}.comment"] = ['nullable', 'string', 'max:2000'];
        }
        $validated = $this->validate($rules);
        $submittedQuestionIds = array_map('intval', array_keys($validated['answers']));
        $expectedQuestionIds = $questions->modelKeys();
        sort($submittedQuestionIds);
        sort($expectedQuestionIds);
        if ($submittedQuestionIds !== $expectedQuestionIds) {
            throw ValidationException::withMessages([
                'answers' => 'La encuesta cambió. Actualiza la página antes de enviarla.',
            ]);
        }

        DB::transaction(function () use ($validated, $expectedQuestionIds): void {
            $surveyRequest = PracticeSurveyRequest::query()
                ->with('version.questions')
                ->lockForUpdate()
                ->findOrFail($this->surveyRequestId);
            $this->authorizeRequest($surveyRequest);
            if ($surveyRequest->status !== PracticeSurveyRequestStatus::Requested) {
                throw ValidationException::withMessages([
                    'survey' => 'Esta encuesta ya no está pendiente de respuesta.',
                ]);
            }

            $currentQuestionIds = $surveyRequest->version->questions->modelKeys();
            sort($currentQuestionIds);
            if ($currentQuestionIds !== $expectedQuestionIds) {
                throw ValidationException::withMessages([
                    'answers' => 'La encuesta cambió. Actualiza la página antes de enviarla.',
                ]);
            }

            foreach ($surveyRequest->version->questions as $question) {
                $answer = $validated['answers'][$question->id];
                $surveyRequest->answers()->create([
                    'practice_survey_question_id' => $question->id,
                    'rating' => $answer['rating'],
                    'comment' => $question->allow_comment ? ($answer['comment'] ?? null) : null,
                ]);
            }
            $surveyRequest->update([
                'status' => PracticeSurveyRequestStatus::Responded,
                'responded_at' => now(),
            ]);
        });

        session()->flash('status', 'Gracias. Tu respuesta fue enviada.');
        $this->redirectRoute('supervisor.practice-surveys.index');
    }

    public function render(): View
    {
        $surveyRequest = $this->authorizedRequest();

        return view('livewire.supervisor.practice-surveys.answer', [
            'surveyRequest' => $surveyRequest,
        ])->layout('layouts.app', ['title' => 'Responder encuesta de satisfacción']);
    }

    private function authorizedRequest(): PracticeSurveyRequest
    {
        $surveyRequest = PracticeSurveyRequest::query()
            ->with(['practiceRequest.company:id,name', 'version.questions'])
            ->findOrFail($this->surveyRequestId);
        $this->authorizeRequest($surveyRequest);
        abort_unless($surveyRequest->status === PracticeSurveyRequestStatus::Requested, 404);

        return $surveyRequest;
    }

    private function authorizeRequest(PracticeSurveyRequest $surveyRequest): void
    {
        $user = auth()->user();
        abort_unless(
            $user?->role === UserRole::Supervisor
            && $user->isSupervisor()
            && $user->email_verified_at !== null
            && $surveyRequest->supervisor_id === $user->id,
            404,
        );
    }
}
