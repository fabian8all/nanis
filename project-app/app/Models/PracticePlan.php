<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['practice_request_id'])]
class PracticePlan extends Model
{
    public function practiceRequest(): BelongsTo
    {
        return $this->belongsTo(PracticeRequest::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PracticePlanVersion::class);
    }

    public function latestVersion(): HasOne
    {
        return $this->hasOne(PracticePlanVersion::class)->latestOfMany('version');
    }
}
