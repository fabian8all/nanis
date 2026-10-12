<?php

namespace App\Models;

use App\Enums\PracticeTerminationLetterStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'practice_request_id',
    'file_path',
    'original_name',
    'mime_type',
    'file_size',
    'status',
    'review_comment',
    'reviewed_by',
    'submitted_at',
    'reviewed_at',
])]
class PracticeTerminationLetter extends Model
{
    protected function casts(): array
    {
        return [
            'status' => PracticeTerminationLetterStatus::class,
            'file_size' => 'integer',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function practiceRequest(): BelongsTo
    {
        return $this->belongsTo(PracticeRequest::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
