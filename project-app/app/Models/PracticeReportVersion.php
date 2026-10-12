<?php

namespace App\Models;

use App\Enums\PracticeReportStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'practice_report_id',
    'version',
    'status',
    'company_name',
    'area',
    'period',
    'objectives',
    'activities',
    'outcomes',
    'conclusions',
    'recommendations',
    'submitted_at',
    'reviewed_by',
    'reviewed_at',
    'review_comment',
    'section_comments',
])]
class PracticeReportVersion extends Model
{
    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'status' => PracticeReportStatus::class,
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'section_comments' => 'array',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(PracticeReport::class, 'practice_report_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PracticeReportAttachment::class, 'practice_report_version_id');
    }
}
