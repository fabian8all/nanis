<?php

namespace Database\Seeders;

use App\Enums\RubricType;
use App\Models\Rubric;
use App\Models\RubricCriterion;
use App\Models\RubricVersion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (RubricType::cases() as $type) {
            $rubric = Rubric::query()->firstOrCreate(['type' => $type->value]);
            if ($rubric->versions()->exists()) {
                continue;
            }

            $version = RubricVersion::query()->create([
                'rubric_id' => $rubric->id,
                'version' => 1,
                'status' => 'draft',
            ]);
            $criteria = $type === RubricType::Supervisor
                ? [
                    'Capacidad de aprendizaje de habilidades para el área',
                    'Actitud hacia el trabajo',
                    'Actitud y conducta',
                    'Responsabilidad',
                ]
                : [];

            foreach ($criteria as $position => $name) {
                RubricCriterion::query()->create([
                    'rubric_version_id' => $version->id,
                    'name' => $name,
                    'position' => $position + 1,
                    'weight' => 0,
                    'scale' => [],
                ]);
            }
        }
    }
}
