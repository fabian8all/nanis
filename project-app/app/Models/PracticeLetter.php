<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'practice_request_id',
    'coordinator_id',
    'folio_year',
    'folio_number',
    'folio',
    'letter_recipient',
    'position',
    'practice_location',
    'start_date',
    'end_date',
    'issued_at',
])]
class PracticeLetter extends Model
{
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'issued_at' => 'datetime',
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
