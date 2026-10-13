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
use App\Models\PracticePlanVersion;
use App\Models\PracticeReportVersion;
use App\Models\PracticeRequest;
use App\Models\PracticeRequestReview;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DevelopmentDatasetSeeder extends Seeder
{
    private const COMPANY_NAMES = [
        'Hotel Bahía Serena',
        'Operadora Costa Azul',
        'Hotel Jardín del Pacífico',
        'Viajes Colimán',
        'Centro Ecoturístico La Ceiba',
        'Hotel Volcán de Fuego',
        'Restaurante Nube de Sal',
        'Hacienda Los Laureles',
        'Posada Río Verde',
        'Agencia Horizonte Viajero',
        'Hotel Brisa de Manzanillo',
        'Parque Aventura del Sur',
        'Casa del Café y el Mar',
        'Marina Puerto Escondido',
        'Hotel Plaza de las Palmas',
        'Tour Operadora Senderos',
        'Centro Cultural La Estación',
        'Cabañas Bosque de Niebla',
        'Restaurante El Faro Dorado',
        'Hotel Camino Real de Colima',
    ];

    private User $coordinator;

    private User $supervisor;

    private Company $supervisorCompany;

    private string $coordinatorEmail;

    private string $supervisorEmail;

    private string $userEmailDomain;

    /** @var list<Company> */
    private array $companies;

    /** @var list<User> */
    private array $tutors;

    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException('Development data may only be seeded in local or testing environments.');
        }

        $password = config('demo.password');
        if (! is_string($password) || trim($password) === '') {
            throw new RuntimeException('Set DEMO_PASSWORD in the local .env before seeding the development dataset.');
        }

        $this->validateEmailConfiguration();

        DB::transaction(function () use ($password): void {
            $this->seedCompanies();
            $this->seedAccounts($password);
            $this->seedPractices();
        });

        $this->command?->info('Dataset demo listo: 20 empresas, 1 coordinador, 5 asesores, 1 supervisor y 50 alumnos.');
        $this->command?->info('Se crearon prácticas sintéticas distribuidas desde solicitud hasta reporte final aprobado.');
        $this->command?->warn('Las contraseñas usan DEMO_PASSWORD del .env local; nunca reutilizar estas cuentas en producción.');
    }

    private function seedCompanies(): void
    {
        $this->companies = [];

        foreach (self::COMPANY_NAMES as $index => $name) {
            $number = $index + 1;
            $company = Company::query()->firstOrCreate(
                ['name' => $name],
                [
                    'address' => "Av. Ficticia {$number}, Colima",
                    'phone' => sprintf('312-555-%04d', $number),
                    'email' => sprintf('contacto%02d@empresa-demo.test', $number),
                    'contact_person' => "Contacto de demostración {$number}",
                ],
            );

            if (! $company->is_active) {
                $company->forceFill(['is_active' => true])->save();
            }

            $this->companies[] = $company;
        }

        $this->supervisorCompany = $this->companies[0];
    }

    private function seedAccounts(string $password): void
    {
        $this->coordinator = $this->upsertAccount(
            $this->coordinatorEmail,
            'Coordinadora de demostración',
            UserRole::Coordinator,
            $password,
        );

        $this->tutors = [];
        foreach (range(1, 5) as $number) {
            $this->tutors[] = $this->upsertAccount(
                $this->institutionalEmail('asesor', $number),
                sprintf('Asesor de demostración %02d', $number),
                UserRole::Tutor,
                $password,
            );
        }

        $this->supervisor = $this->upsertAccount(
            $this->supervisorEmail,
            'Supervisor de demostración',
            UserRole::Supervisor,
            $password,
            $this->supervisorCompany->id,
        );

        foreach (range(1, 50) as $number) {
            $this->upsertAccount(
                $this->institutionalEmail('alumno', $number),
                sprintf('Alumno de demostración %02d', $number),
                UserRole::Student,
                $password,
            );
        }
    }

    private function upsertAccount(
        string $email,
        string $name,
        UserRole $role,
        string $password,
        ?int $companyId = null,
    ): User {
        return User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'role' => $role,
                'company_id' => $companyId,
                'email_verified_at' => now(),
            ],
        );
    }

    private function seedPractices(): void
    {
        foreach (range(1, 50) as $number) {
            $student = User::query()->where('email', $this->institutionalEmail('alumno', $number))->firstOrFail();
            $stage = $this->stageFor($number);
            $company = $number >= 26
                ? $this->supervisorCompany
                : $this->companies[($number - 1) % count($this->companies)];
            $tutor = $this->tutors[($number - 1) % count($this->tutors)];
            $startDate = today()->subMonths(2)->subDays($number)->toDateString();
            $endDate = today()->addMonth()->addDays($number)->toDateString();
            $requestStatus = $stage === 'returned'
                ? PracticeRequestStatus::Returned
                : ($stage === 'pending' ? PracticeRequestStatus::Pending : PracticeRequestStatus::Approved);

            $request = PracticeRequest::query()->updateOrCreate(
                ['user_id' => $student->id],
                [
                    'tutor_id' => $tutor->id,
                    'company_id' => $company->id,
                    'ucol_email' => $student->email,
                    'nss' => sprintf('%011d', 90000000000 + $number),
                    'account_number' => sprintf('%08d', 90000000 + $number),
                    'grade_group' => sprintf('8%c - DEMO', chr(65 + (($number - 1) % 5))),
                    'start_date' => $startDate,
                    'practice_location' => 'Colima (dato ficticio)',
                    'letter_recipient' => 'Coordinación de demostración',
                    'position' => 'Practicante de turismo',
                    'end_date' => $endDate,
                    'status' => $requestStatus,
                    'non_responsiva_accepted_at' => now()->subMonths(2),
                ],
            );

            if (in_array($requestStatus, [PracticeRequestStatus::Approved, PracticeRequestStatus::Returned], true)) {
                $decision = $requestStatus;
                PracticeRequestReview::query()->updateOrCreate(
                    [
                        'practice_request_id' => $request->id,
                        'decision' => $decision,
                    ],
                    [
                        'coordinator_id' => $this->coordinator->id,
                        'comment' => $decision === PracticeRequestStatus::Returned
                            ? 'Completa la información señalada para continuar (comentario ficticio).'
                            : 'Solicitud aprobada para datos de demostración.',
                        'reviewed_at' => now()->subDays(max(0, 30 - $number)),
                    ],
                );
            }

            if ($requestStatus === PracticeRequestStatus::Approved) {
                $this->seedPresentationLetter($request, $number);
            }

            if ($number >= 26) {
                $this->seedAcceptance($request, $number, $stage);
                if ($number >= 31) {
                    $this->seedPlan($request, $number, $stage);
                    if ($number >= 41) {
                        $this->seedReport($request, $number, $stage);
                    }
                }
            }
        }
    }

    private function seedPresentationLetter(PracticeRequest $request, int $number): void
    {
        $year = (int) today()->format('Y');
        PracticeLetter::query()->updateOrCreate(
            ['practice_request_id' => $request->id],
            [
                'coordinator_id' => $this->coordinator->id,
                'folio_year' => $year,
                'folio_number' => 900000 + $request->id,
                'folio' => sprintf('DEMO-%d-%06d', $year, $request->id),
                'letter_recipient' => $request->letter_recipient,
                'position' => $request->position,
                'practice_location' => $request->practice_location,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'issued_at' => now()->subDays(max(0, $number - 15)),
            ],
        );
    }

    private function seedAcceptance(PracticeRequest $request, int $number, string $stage): PracticeAcceptanceSubmission
    {
        $status = $stage === 'acceptance_review'
            ? PracticeAcceptanceStatus::PendingReview
            : PracticeAcceptanceStatus::Accepted;
        $path = sprintf('practice-acceptances/development-dataset/%d/acceptance.pdf', $request->id);
        $contents = "%PDF-1.4\n% Documento ficticio de aceptación para pruebas\n%%EOF";
        if (! Storage::disk('local')->put($path, $contents)) {
            throw new RuntimeException("Unable to store the synthetic acceptance file for practice {$request->id}.");
        }

        return $request->acceptanceSubmissions()->updateOrCreate(
            ['version' => 1],
            [
                'supervisor_id' => $this->supervisor->id,
                'file_path' => $path,
                'file_extension' => 'pdf',
                'mime_type' => 'application/pdf',
                'file_size' => strlen($contents),
                'status' => $status,
                'review_comment' => $status === PracticeAcceptanceStatus::Accepted
                    ? 'Carta revisada (dato ficticio).'
                    : null,
                'reviewed_by' => $status === PracticeAcceptanceStatus::Accepted ? $this->coordinator->id : null,
                'submitted_at' => now()->subDays($number),
                'reviewed_at' => $status === PracticeAcceptanceStatus::Accepted ? now()->subDays(max(0, $number - 1)) : null,
            ],
        );
    }

    private function seedPlan(PracticeRequest $request, int $number, string $stage): PracticePlanVersion
    {
        $plan = $request->practicePlan()->firstOrCreate([]);
        $status = match ($stage) {
            'plan_supervisor' => PracticePlanStatus::PendingSupervisorReview,
            'plan_coordinator' => PracticePlanStatus::PendingCoordinatorReview,
            default => PracticePlanStatus::Approved,
        };
        $planVersion = $plan->versions()->updateOrCreate(
            ['version' => 1],
            [
                'status' => $status,
                'submitted_at' => now()->subDays($number),
                'supervisor_reviewed_by' => $status === PracticePlanStatus::PendingSupervisorReview
                    ? null
                    : $this->supervisor->id,
                'supervisor_reviewed_at' => $status === PracticePlanStatus::PendingSupervisorReview
                    ? null
                    : now()->subDays(max(0, $number - 1)),
                'supervisor_review_comment' => $status === PracticePlanStatus::PendingSupervisorReview
                    ? null
                    : 'Plan revisado por supervisor (dato ficticio).',
                'coordinator_reviewed_by' => $status === PracticePlanStatus::Approved
                    ? $this->coordinator->id
                    : null,
                'coordinator_reviewed_at' => $status === PracticePlanStatus::Approved
                    ? now()->subDays(max(0, $number - 2))
                    : null,
                'coordinator_review_comment' => $status === PracticePlanStatus::Approved
                    ? 'Plan aprobado para demostración.'
                    : null,
            ],
        );
        $planVersion->activities()->updateOrCreate(
            ['position' => 1],
            [
                'description' => 'Apoyar en la atención y orientación de visitantes (actividad ficticia).',
                'responsible' => $this->supervisor->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'expected_result' => 'Registrar una experiencia de servicio para demostración.',
            ],
        );

        return $planVersion;
    }

    private function seedReport(PracticeRequest $request, int $number, string $stage): PracticeReportVersion
    {
        $report = $request->practiceReport()->firstOrCreate([]);
        $approved = $stage === 'report_approved';
        $version = $report->versions()->updateOrCreate(
            ['version' => 1],
            [
                'status' => $approved ? PracticeReportStatus::Approved : PracticeReportStatus::PendingTutorReview,
                'company_name' => $request->company->name,
                'area' => 'Servicios turísticos',
                'period' => $request->start_date->format('d/m/Y').' al '.$request->end_date->format('d/m/Y'),
                'objectives' => 'Describir una experiencia ficticia de prácticas para demostración.',
                'activities' => 'Apoyo en orientación, recepción y atención a visitantes (datos sintéticos).',
                'outcomes' => 'Se documentaron resultados ficticios para recorrer la revisión del reporte.',
                'conclusions' => 'Conclusión de ejemplo sin valor institucional.',
                'recommendations' => 'Recomendación de muestra para el prototipo.',
                'submitted_at' => now()->subDays($number),
                'reviewed_by' => $approved ? $request->tutor_id : null,
                'reviewed_at' => $approved ? now()->subDays(max(0, $number - 1)) : null,
                'review_comment' => $approved ? 'Reporte aprobado para demostración.' : null,
                'section_comments' => null,
            ],
        );

        $report->forceFill(['approved_version_id' => $approved ? $version->id : null])->save();

        return $version;
    }

    private function stageFor(int $number): string
    {
        return match (true) {
            $number <= 10 => 'pending',
            $number <= 15 => 'returned',
            $number <= 25 => 'approved_waiting_acceptance',
            $number <= 30 => 'acceptance_review',
            $number <= 35 => 'plan_supervisor',
            $number <= 40 => 'plan_coordinator',
            $number <= 45 => 'report_review',
            default => 'report_approved',
        };
    }

    private function institutionalEmail(string $prefix, int $number): string
    {
        return sprintf('%s%d@%s', $prefix, $number, $this->userEmailDomain);
    }

    private function validateEmailConfiguration(): void
    {
        $coordinatorEmail = config('demo.coordinator_email');
        $supervisorEmail = config('demo.supervisor_email');
        $userEmailDomain = config('demo.user_email_domain');

        if (! is_string($coordinatorEmail) || filter_var($coordinatorEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException('Set a valid DEMO_COORDINATOR_EMAIL in the local .env before seeding.');
        }

        if (! is_string($supervisorEmail) || filter_var($supervisorEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException('Set a valid DEMO_SUPERVISOR_EMAIL in the local .env before seeding.');
        }

        if (! is_string($userEmailDomain) || filter_var('demo@'.$userEmailDomain, FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException('Set a valid DEMO_USER_EMAIL_DOMAIN in the local .env before seeding.');
        }

        $emails = [$coordinatorEmail, $supervisorEmail];
        foreach (['asesor', 'alumno'] as $prefix) {
            foreach (range(1, $prefix === 'asesor' ? 5 : 50) as $number) {
                $emails[] = sprintf('%s%d@%s', $prefix, $number, $userEmailDomain);
            }
        }

        if (count(array_unique($emails)) !== count($emails)) {
            throw new RuntimeException('Demo account email settings must produce 57 unique addresses.');
        }

        $this->coordinatorEmail = $coordinatorEmail;
        $this->supervisorEmail = $supervisorEmail;
        $this->userEmailDomain = $userEmailDomain;
    }
}
