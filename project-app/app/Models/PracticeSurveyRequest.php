<?php

namespace App\Models;

use App\Enums\PracticeSurveyRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'practice_request_id',
    'practice_acceptance_submission_id',
    'supervisor_id',
    'practice_survey_version_id',
    'status',
    'requested_by',
    'requested_at',
    'responded_at',
    'cancelled_by',
    'cancelled_at',
])]
class PracticeSurveyRequest extends Model
{
    protected function casts(): array
    {
        return [
            'status' => PracticeSurveyRequestStatus::class,
            'requested_at' => 'datetime',
            'responded_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (PracticeSurveyRequest $surveyRequest): void {
            if ($surveyRequest->getRawOriginal('status') !== PracticeSurveyRequestStatus::Requested->value) {
                throw new \LogicException('Submitted or cancelled survey requests are immutable.');
            }
        });
    }

    public function practiceRequest(): BelongsTo
    {
        return $this->belongsTo(PracticeRequest::class);
    }

    public function acceptanceSubmission(): BelongsTo
    {
        return $this->belongsTo(PracticeAcceptanceSubmission::class, 'practice_acceptance_submission_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(PracticeSurveyVersion::class, 'practice_survey_version_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function canceller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(PracticeSurveyAnswer::class);
    }
}
