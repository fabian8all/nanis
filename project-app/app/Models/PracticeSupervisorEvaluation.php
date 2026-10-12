<?php

namespace App\Models;

use App\Enums\PracticeEvaluationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'practice_request_id',
    'practice_acceptance_submission_id',
    'supervisor_id',
    'rubric_version_id',
    'status',
    'score_total',
    'submitted_at',
])]
class PracticeSupervisorEvaluation extends Model
{
    protected function casts(): array
    {
        return [
            'status' => PracticeEvaluationStatus::class,
            'score_total' => 'float',
            'submitted_at' => 'datetime',
        ];
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

    public function rubricVersion(): BelongsTo
    {
        return $this->belongsTo(RubricVersion::class);
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(PracticeSupervisorEvaluationCriterion::class)->orderBy('position');
    }

    public function isSubmitted(): bool
    {
        return $this->status === PracticeEvaluationStatus::Submitted;
    }
}
