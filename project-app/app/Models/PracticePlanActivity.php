<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'practice_plan_version_id',
    'position',
    'description',
    'responsible',
    'start_date',
    'end_date',
    'expected_result',
])]
class PracticePlanActivity extends Model
{
    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(PracticePlanVersion::class, 'practice_plan_version_id');
    }
}
