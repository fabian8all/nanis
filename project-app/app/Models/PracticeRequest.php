<?php

namespace App\Models;

use App\Enums\PracticeRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'company_id',
    'ucol_email',
    'grade_group',
    'start_date',
    'practice_location',
    'letter_recipient',
    'position',
    'end_date',
    'status',
    'non_responsiva_accepted_at',
])]
class PracticeRequest extends Model
{
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => PracticeRequestStatus::class,
            'non_responsiva_accepted_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
