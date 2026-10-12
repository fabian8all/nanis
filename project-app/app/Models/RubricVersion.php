<?php

namespace App\Models;

use App\Services\RubricScoreCalculator;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

#[Fillable(['rubric_id', 'version', 'status', 'published_at'])]
class RubricVersion extends Model
{
    protected function casts(): array
    {
        return ['version' => 'integer', 'published_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::updating(function (RubricVersion $version): void {
            if ($version->getOriginal('status') === 'published') {
                throw new \LogicException('Published rubric versions are immutable.');
            }
        });
        static::deleting(function (RubricVersion $version): void {
            if ($version->status === 'published') {
                throw new \LogicException('Published rubric versions cannot be deleted.');
            }
        });
    }

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(Rubric::class);
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(RubricCriterion::class)->orderBy('position');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function publish(): void
    {
        DB::transaction(function (): void {
            $rubric = Rubric::query()->lockForUpdate()->findOrFail($this->rubric_id);
            $this->refresh();
            app(RubricScoreCalculator::class)->validateVersion($this);
            $this->update(['status' => 'published', 'published_at' => now()]);
            $rubric->update(['current_version_id' => $this->id]);
        });
    }
}
