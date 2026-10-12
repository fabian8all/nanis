<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['rubric_version_id', 'name', 'description', 'position', 'weight', 'scale'])]
class RubricCriterion extends Model
{
    protected function casts(): array
    {
        return ['position' => 'integer', 'weight' => 'float', 'scale' => 'array'];
    }

    protected static function booted(): void
    {
        static::saving(function (RubricCriterion $criterion): void {
            if ($criterion->version()->where('status', 'published')->exists()) {
                throw new \LogicException('Published rubric criteria are immutable.');
            }
        });
        static::deleting(function (RubricCriterion $criterion): void {
            if ($criterion->version()->where('status', 'published')->exists()) {
                throw new \LogicException('Published rubric criteria cannot be deleted.');
            }
        });
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(RubricVersion::class, 'rubric_version_id');
    }
}
