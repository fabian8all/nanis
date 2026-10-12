<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'practice_report_version_id',
    'file_path',
    'original_name',
    'mime_type',
    'file_size',
])]
class PracticeReportAttachment extends Model
{
    protected function casts(): array
    {
        return ['file_size' => 'integer'];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(PracticeReportVersion::class, 'practice_report_version_id');
    }
}
