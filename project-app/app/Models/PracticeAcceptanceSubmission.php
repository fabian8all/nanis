<?php

namespace App\Models;

use App\Enums\PracticeAcceptanceStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'practice_request_id',
    'supervisor_id',
    'version',
    'file_path',
    'file_extension',
    'mime_type',
    'file_size',
    'status',
    'review_comment',
    'reviewed_by',
    'submitted_at',
    'reviewed_at',
])]
class PracticeAcceptanceSubmission extends Model
{
    protected function casts(): array
    {
        return [
            'status' => PracticeAcceptanceStatus::class,
            'version' => 'integer',
            'file_size' => 'integer',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function practiceRequest(): BelongsTo
    {
        return $this->belongsTo(PracticeRequest::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
