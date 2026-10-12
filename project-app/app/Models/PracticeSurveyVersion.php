<?php

namespace App\Models;

use App\Enums\PracticeSurveyVersionStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

#[Fillable(['version', 'status', 'published_at'])]
class PracticeSurveyVersion extends Model
{
    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'status' => PracticeSurveyVersionStatus::class,
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (PracticeSurveyVersion $version): void {
            if ($version->getRawOriginal('status') === PracticeSurveyVersionStatus::Published->value) {
                throw new \LogicException('Published survey versions are immutable.');
            }
        });
        static::deleting(function (PracticeSurveyVersion $version): void {
            if ($version->status === PracticeSurveyVersionStatus::Published) {
                throw new \LogicException('Published survey versions cannot be deleted.');
            }
        });
    }

    public function questions(): HasMany
    {
        return $this->hasMany(PracticeSurveyQuestion::class)->orderBy('position');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(PracticeSurveyRequest::class);
    }

    public function isPublished(): bool
    {
        return $this->status === PracticeSurveyVersionStatus::Published;
    }

    public function publish(): void
    {
        DB::transaction(function (): void {
            PracticeSurveyVersion::query()
                ->orderByDesc('version')
                ->lockForUpdate()
                ->firstOrFail();

            $this->refresh();
            abort_if($this->isPublished(), 403, 'La versión ya está publicada.');
            abort_if($this->questions()->count() === 0, 422, 'Agrega al menos una pregunta antes de publicar.');
            $this->update([
                'status' => PracticeSurveyVersionStatus::Published,
                'published_at' => now(),
            ]);
        });
    }
}
