<?php

namespace App\Models;

use App\Enums\PracticeSurveyVersionStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['practice_survey_version_id', 'question', 'position', 'allow_comment'])]
class PracticeSurveyQuestion extends Model
{
    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'allow_comment' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (PracticeSurveyQuestion $question): void {
            if ($question->version()->first()?->status === PracticeSurveyVersionStatus::Published) {
                throw new \LogicException('Questions in published survey versions are immutable.');
            }
        });
        static::deleting(function (PracticeSurveyQuestion $question): void {
            if ($question->version()->first()?->status === PracticeSurveyVersionStatus::Published) {
                throw new \LogicException('Questions in published survey versions cannot be deleted.');
            }
        });
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(PracticeSurveyVersion::class, 'practice_survey_version_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(PracticeSurveyAnswer::class);
    }
}
