<?php

namespace App\Models;

use App\Enums\RubricType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['type', 'current_version_id'])]
class Rubric extends Model
{
    protected function casts(): array
    {
        return ['type' => RubricType::class];
    }

    public function versions(): HasMany
    {
        return $this->hasMany(RubricVersion::class)->orderByDesc('version');
    }

    public function currentVersion(): BelongsTo
    {
        return $this->belongsTo(RubricVersion::class, 'current_version_id');
    }
}
