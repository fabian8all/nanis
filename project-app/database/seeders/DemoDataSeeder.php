<?php

namespace Database\Seeders;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\PracticeAcceptanceSubmission;
use App\Models\PracticeLetter;
use App\Models\PracticeRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException('Demo data may only be seeded in local or testing environments.');
        }

        $password = Str::password(24);
        $accounts = [
            'coordinacion@nanis.test' => ['Coordinación Demo', UserRole::Coordinator],
            'asesor@nanis.test' => ['Asesor Demo', UserRole::Tutor],
            'supervisor@nanis.test' => ['Supervisor Demo', UserRole::Supervisor],
            'alumno@nanis.test' => ['Alumno Demo', UserRole::Student],
        ];
        $existing = User::query()->whereIn('email', array_keys($accounts))->get()->keyBy('email');
        $created = [];
        foreach ($accounts as $email => [$name, $role]) {
            $user = $existing->get($email);
            if (! $user) {
                $user = User::query()->create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role,
                ]);
                $created[] = $email;
            }
        }

        $coordinator = User::query()->where('email', 'coordinacion@nanis.test')->firstOrFail();
        $tutor = User::query()->where('email', 'asesor@nanis.test')->firstOrFail();
        $student = User::query()->where('email', 'alumno@nanis.test')->firstOrFail();
        $company = Company::query()->firstOrCreate(
            ['name' => 'Empresa Demo de Prácticas'],
            [
                'address' => 'Av. de Prueba 100',
                'phone' => '000-000-0000',
                'email' => 'contacto@empresa-demo.test',
                'contact_person' => 'Contacto Demo',
                'is_active' => true,
            ],
        );
        $supervisor = User::query()->where('email', 'supervisor@nanis.test')->firstOrFail();
        if ($supervisor->company_id !== $company->id) {
            $supervisor->update(['company_id' => $company->id]);
        }

        $request = PracticeRequest::query()->firstOrCreate(
            ['user_id' => $student->id, 'company_id' => $company->id],
            [
                'tutor_id' => $tutor->id,
                'ucol_email' => $student->email,
                'nss' => '00000000000',
                'account_number' => '00000000',
                'grade_group' => '8A - DEMO',
                'start_date' => now()->subMonths(2)->toDateString(),
                'practice_location' => 'Colima (dato de prueba)',
                'letter_recipient' => 'Dirección Demo',
                'position' => 'Practicante Demo',
                'end_date' => now()->addMonth()->toDateString(),
                'status' => PracticeRequestStatus::Approved,
                'non_responsiva_accepted_at' => now(),
            ],
        );
        if ($request->tutor_id !== $tutor->id) {
            $request->update(['tutor_id' => $tutor->id]);
        }

        PracticeLetter::query()->firstOrCreate(
            ['practice_request_id' => $request->id],
            [
                'coordinator_id' => $coordinator->id,
                'folio_year' => (int) now()->format('Y'),
                'folio_number' => 9999,
                'folio' => 'DEMO-'.now()->format('Y').'-0001',
                'letter_recipient' => $request->letter_recipient,
                'position' => $request->position,
                'practice_location' => $request->practice_location,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'issued_at' => now(),
            ],
        );

        $acceptancePath = 'practice-acceptances/demo/aceptacion-demo.pdf';
        if (! Storage::disk('local')->put($acceptancePath, "%PDF-1.4\n% Datos sintéticos de demostración\n%%EOF")) {
            throw new RuntimeException('Could not store the demo acceptance file.');
        }
        PracticeAcceptanceSubmission::query()->firstOrCreate(
            ['practice_request_id' => $request->id, 'version' => 1],
            [
                'supervisor_id' => $supervisor->id,
                'file_path' => $acceptancePath,
                'file_extension' => 'pdf',
                'mime_type' => 'application/pdf',
                'file_size' => Storage::disk('local')->size($acceptancePath),
                'status' => PracticeAcceptanceStatus::Accepted,
                'reviewed_by' => $coordinator->id,
                'reviewed_at' => now(),
                'submitted_at' => now(),
            ],
        );

        $plan = $request->practicePlan()->firstOrCreate([]);
        $planVersion = $plan->versions()->firstOrCreate(
            ['version' => 1],
            [
                'status' => PracticePlanStatus::Approved,
                'submitted_at' => now(),
                'supervisor_reviewed_by' => $supervisor->id,
                'supervisor_reviewed_at' => now(),
                'coordinator_reviewed_by' => $coordinator->id,
                'coordinator_reviewed_at' => now(),
            ],
        );
        $planVersion->activities()->firstOrCreate(
            ['position' => 1],
            [
                'description' => 'Documentar un proceso de atención (actividad ficticia)',
                'responsible' => 'Supervisor Demo',
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'expected_result' => 'Proceso documentado para prueba',
            ],
        );

        $report = $request->practiceReport()->firstOrCreate([]);
        $reportVersion = $report->versions()->firstOrCreate(
            ['version' => 1],
            [
                'status' => PracticeReportStatus::Approved,
                'company_name' => $company->name,
                'area' => 'Área Demo',
                'period' => 'Periodo de demostración',
                'objectives' => 'Objetivos sintéticos para probar la aplicación.',
                'activities' => 'Actividades ficticias de demostración.',
                'outcomes' => 'Resultados de prueba, no institucionales.',
                'conclusions' => 'Conclusiones sintéticas.',
                'recommendations' => 'Recomendaciones ficticias.',
                'submitted_at' => now(),
                'reviewed_by' => $tutor->id,
                'reviewed_at' => now(),
                'review_comment' => 'Aprobación de demostración.',
            ],
        );
        if ($report->approved_version_id !== $reportVersion->id) {
            $report->update(['approved_version_id' => $reportVersion->id]);
        }

        $this->command?->info('Datos demo listos. No se generó una evaluación porque la rúbrica Tutor no está publicada.');
        if ($created !== []) {
            $this->command?->info('Contraseña temporal de las cuentas nuevas: '.$password);
            $this->command?->table(['Perfil', 'Correo'], array_map(
                fn (string $email): array => [$accounts[$email][0], $email],
                $created,
            ));
            $this->command?->warn('Guarda la contraseña ahora: no se muestra de nuevo en ejecuciones posteriores.');
        } else {
            $this->command?->info('Las cuentas demo ya existían; se conservaron sus contraseñas actuales.');
        }
    }
}
