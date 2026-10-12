<?php

namespace App\Models;

use App\Enums\PracticeSurveyRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['practice_survey_request_id', 'practice_survey_question_id', 'rating', 'comment'])]
class PracticeSurveyAnswer extends Model
{
    protected function casts(): array
    {
        return ['rating' => 'integer'];
    }

    protected static function booted(): void
    {
        static::creating(function (PracticeSurveyAnswer $answer): void {
            if ($answer->surveyRequest()->first()?->status !== PracticeSurveyRequestStatus::Requested) {
                throw new \LogicException('Submitted survey answers are immutable.');
            }
        });
        static::updating(function (): never {
            throw new \LogicException('Submitted survey answers are immutable.');
        });
        static::deleting(function (PracticeSurveyAnswer $answer): void {
            if ($answer->surveyRequest()->first()?->status !== PracticeSurveyRequestStatus::Requested) {
                throw new \LogicException('Submitted survey answers cannot be deleted.');
            }
        });
    }

    public function surveyRequest(): BelongsTo
    {
        return $this->belongsTo(PracticeSurveyRequest::class, 'practice_survey_request_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(PracticeSurveyQuestion::class, 'practice_survey_question_id');
    }
}
