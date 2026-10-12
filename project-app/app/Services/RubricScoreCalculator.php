<?php

namespace App\Services;

use App\Models\RubricVersion;
use Illuminate\Validation\ValidationException;

class RubricScoreCalculator
{
    public function validateVersion(RubricVersion $version): void
    {
        $criteria = $version->criteria()->get();
        $errors = [];
        if ($criteria->isEmpty()) {
            $errors['criteria'] = 'Agrega al menos un criterio antes de publicar.';
        }
        $weights = 0.0;
        foreach ($criteria as $criterion) {
            $weights += (float) $criterion->weight;
            $scale = $criterion->scale;
            if (blank($criterion->name) || ! is_array($scale) || count($scale) < 2) {
                $errors["criteria.{$criterion->id}"] = 'Cada criterio requiere nombre y al menos dos niveles.';

                continue;
            }
            $values = collect($scale)->pluck('value');
            if ($values->contains(fn ($value) => ! is_numeric($value) || $value < 0) ||
                $values->map(fn ($value) => (string) $value)->unique()->count() !== $values->count() ||
                collect($scale)->contains(fn ($level) => ! is_array($level) || ! is_string($level['label'] ?? null) || trim($level['label']) === '')) {
                $errors["criteria.{$criterion->id}.scale"] = 'Cada nivel requiere un valor numérico único no negativo y una etiqueta.';
            }
            if ($criterion->weight <= 0 || $criterion->weight > 100) {
                $errors["criteria.{$criterion->id}.weight"] = 'La ponderación debe ser mayor que cero y hasta 100.';
            }
        }
        if (abs($weights - 100) > 0.00001) {
            $errors['weights'] = 'Las ponderaciones deben sumar exactamente 100%.';
        }
        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    /** @param array<int, int|float> $scores Keyed by criterion ID */
    public function calculate(RubricVersion $version, array $scores): float
    {
        $this->validateVersion($version);
        $total = 0.0;
        foreach ($version->criteria as $criterion) {
            if (! array_key_exists($criterion->id, $scores)) {
                throw ValidationException::withMessages(["scores.{$criterion->id}" => 'Falta el puntaje del criterio.']);
            }
            $score = $scores[$criterion->id];
            $maximum = max(array_column($criterion->scale, 'value'));
            $values = array_map('floatval', array_column($criterion->scale, 'value'));
            if (! is_numeric($score) || ! in_array((float) $score, $values, true)) {
                throw ValidationException::withMessages(["scores.{$criterion->id}" => 'El puntaje no pertenece a la escala.']);
            }
            $total += ((float) $score / $maximum) * $criterion->weight;
        }

        return round($total, 2);
    }
}
