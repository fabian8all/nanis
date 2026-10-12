<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['practice_request_id', 'approved_version_id'])]
class PracticeReport extends Model
{
    public function practiceRequest(): BelongsTo
    {
        return $this->belongsTo(PracticeRequest::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PracticeReportVersion::class);
    }

    public function approvedVersion(): BelongsTo
    {
        return $this->belongsTo(PracticeReportVersion::class, 'approved_version_id');
    }
}
