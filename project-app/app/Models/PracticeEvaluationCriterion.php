<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'practice_evaluation_id',
    'rubric_criterion_id',
    'name',
    'description',
    'position',
    'weight',
    'scale',
    'score',
    'score_label',
    'contribution',
])]
class PracticeEvaluationCriterion extends Model
{
    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'weight' => 'float',
            'scale' => 'array',
            'score' => 'float',
            'contribution' => 'float',
        ];
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(PracticeEvaluation::class, 'practice_evaluation_id');
    }

    public function rubricCriterion(): BelongsTo
    {
        return $this->belongsTo(RubricCriterion::class);
    }
}
