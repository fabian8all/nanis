<?php

namespace App\Models;

use App\Enums\PracticeEvaluationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'practice_request_id',
    'tutor_id',
    'practice_report_version_id',
    'rubric_version_id',
    'status',
    'score_total',
    'submitted_at',
])]
class PracticeEvaluation extends Model
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

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function reportVersion(): BelongsTo
    {
        return $this->belongsTo(PracticeReportVersion::class, 'practice_report_version_id');
    }

    public function rubricVersion(): BelongsTo
    {
        return $this->belongsTo(RubricVersion::class);
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(PracticeEvaluationCriterion::class)->orderBy('position');
    }

    public function isSubmitted(): bool
    {
        return $this->status === PracticeEvaluationStatus::Submitted;
    }
}
