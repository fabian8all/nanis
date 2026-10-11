<?php

namespace App\Models;

use App\Enums\PracticeRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['practice_request_id', 'coordinator_id', 'decision', 'comment', 'reviewed_at'])]
class PracticeRequestReview extends Model
{
    protected function casts(): array
    {
        return [
            'decision' => PracticeRequestStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    public function practiceRequest(): BelongsTo
    {
        return $this->belongsTo(PracticeRequest::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }
}
