<?php

namespace App\Models;

use App\Enums\PracticeRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'user_id',
    'tutor_id',
    'company_id',
    'ucol_email',
    'nss',
    'account_number',
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
            'nss' => 'encrypted',
            'account_number' => 'encrypted',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(PracticeRequestReview::class);
    }

    public function letter(): HasOne
    {
        return $this->hasOne(PracticeLetter::class);
    }

    public function acceptanceSubmissions(): HasMany
    {
        return $this->hasMany(PracticeAcceptanceSubmission::class);
    }

    public function latestAcceptanceSubmission(): HasOne
    {
        return $this->hasOne(PracticeAcceptanceSubmission::class)->latestOfMany('version');
    }

    public function practicePlan(): HasOne
    {
        return $this->hasOne(PracticePlan::class);
    }

    public function practiceReport(): HasOne
    {
        return $this->hasOne(PracticeReport::class);
    }

    public function terminationLetter(): HasOne
    {
        return $this->hasOne(PracticeTerminationLetter::class);
    }

    public function practiceEvaluation(): HasOne
    {
        return $this->hasOne(PracticeEvaluation::class);
    }

    public function practiceSupervisorEvaluation(): HasOne
    {
        return $this->hasOne(PracticeSupervisorEvaluation::class);
    }
}
