<?php

namespace App\Models;

use App\Enums\PracticePlanStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'practice_plan_id',
    'version',
    'status',
    'submitted_at',
    'supervisor_reviewed_by',
    'supervisor_reviewed_at',
    'supervisor_review_comment',
    'coordinator_reviewed_by',
    'coordinator_reviewed_at',
    'coordinator_review_comment',
])]
class PracticePlanVersion extends Model
{
    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'status' => PracticePlanStatus::class,
            'submitted_at' => 'datetime',
            'supervisor_reviewed_at' => 'datetime',
            'coordinator_reviewed_at' => 'datetime',
        ];
    }

    public function practicePlan(): BelongsTo
    {
        return $this->belongsTo(PracticePlan::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(PracticePlanActivity::class)->orderBy('position');
    }

    public function supervisorReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_reviewed_by');
    }

    public function coordinatorReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_reviewed_by');
    }
}
