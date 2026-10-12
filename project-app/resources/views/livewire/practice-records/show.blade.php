<section>
    <h1 class="h3 mb-3">Expediente de práctica</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h5">{{ $practiceRequest->student->name }} — {{ $practiceRequest->company->name }}</h2>
            <p class="mb-0">Periodo: {{ $practiceRequest->start_date->format('d/m/Y') }} al {{ $practiceRequest->end_date->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="alert {{ $isComplete ? 'alert-success' : 'alert-warning' }}" role="status">
        <strong>Estado del expediente: {{ $isComplete ? 'Completo' : 'Incompleto' }}.</strong>
        @if (! $isComplete)
            Los elementos pendientes se indican a continuación.
        @endif
    </div>

    <section class="mb-4" aria-labelledby="closure-evidence-heading">
        <h2 id="closure-evidence-heading" class="h5">Requisitos de cierre</h2>
        <ul class="list-group">
            @foreach ($requirements as $requirement)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $requirement['label'] }}</span>
                    <span class="badge {{ $requirement['complete'] ? 'text-bg-success' : 'text-bg-warning' }}">
                        {{ $requirement['status'] }}
                    </span>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="mb-4" aria-labelledby="termination-letter-heading">
        <h2 id="termination-letter-heading" class="h5">Carta de término</h2>
        @if ($practiceRequest->terminationLetter?->status === \App\Enums\PracticeTerminationLetterStatus::Accepted)
            <p>La carta de término fue aceptada.</p>
            <a class="btn btn-outline-primary btn-sm" href="{{ route($isCoordinator ? 'coordinacion.practice-termination-letters.file' : 'alumno.practice-termination-letters.file', $practiceRequest->terminationLetter) }}">
                Descargar {{ $practiceRequest->terminationLetter->original_name }}
            </a>
        @else
            <p class="text-secondary">La carta de término aún no está aceptada.</p>
            @if (! $isCoordinator)
                <a class="btn btn-outline-primary btn-sm" href="{{ route('alumno.practice-termination-letters.upload', $practiceRequest) }}">
                    Consultar carta de término
                </a>
            @endif
        @endif
    </section>

    <section class="mb-4" aria-labelledby="approved-report-heading">
        <h2 id="approved-report-heading" class="h5">Reporte final aprobado</h2>
        @if ($approvedReport && $approvedReport->status === \App\Enums\PracticeReportStatus::Approved)
            <dl class="row">
                <dt class="col-sm-3">Empresa</dt>
                <dd class="col-sm-9">{{ $approvedReport->company_name }}</dd>
                <dt class="col-sm-3">Área</dt>
                <dd class="col-sm-9">{{ $approvedReport->area }}</dd>
                <dt class="col-sm-3">Periodo</dt>
                <dd class="col-sm-9">{{ $approvedReport->period }}</dd>
                <dt class="col-sm-3">Objetivos</dt>
                <dd class="col-sm-9">{{ $approvedReport->objectives }}</dd>
                <dt class="col-sm-3">Actividades</dt>
                <dd class="col-sm-9">{{ $approvedReport->activities }}</dd>
                <dt class="col-sm-3">Resultados y aprendizajes</dt>
                <dd class="col-sm-9">{{ $approvedReport->outcomes }}</dd>
                <dt class="col-sm-3">Conclusiones</dt>
                <dd class="col-sm-9">{{ $approvedReport->conclusions }}</dd>
                <dt class="col-sm-3">Recomendaciones</dt>
                <dd class="col-sm-9">{{ $approvedReport->recommendations }}</dd>
            </dl>

            @if ($approvedReport->attachments->isNotEmpty())
                <h3 class="h6">Anexos del reporte aprobado</h3>
                <ul>
                    @foreach ($approvedReport->attachments as $attachment)
                        <li>
                            <a href="{{ route('practice-reports.attachments.download', $attachment) }}">{{ $attachment->original_name }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        @else
            <p class="text-secondary">No hay un reporte final aprobado.</p>
            @if (! $isCoordinator)
                <a class="btn btn-outline-primary btn-sm" href="{{ route('alumno.practice-reports.show', $practiceRequest) }}">
                    Consultar reporte final
                </a>
            @endif
        @endif
    </section>

    <div class="row g-4">
        <section class="col-md-6" aria-labelledby="supervisor-evaluation-heading">
            <h2 id="supervisor-evaluation-heading" class="h5">Evaluación terminal del Supervisor</h2>
            @if ($practiceRequest->practiceSupervisorEvaluation?->status === \App\Enums\PracticeEvaluationStatus::Submitted)
                <p>Evaluación enviada · Resultado: {{ number_format($practiceRequest->practiceSupervisorEvaluation->score_total, 2) }}/100</p>
                <a href="{{ route($isCoordinator ? 'coordinacion.practice-supervisor-evaluations.show' : 'alumno.practice-supervisor-evaluations.show', $practiceRequest->practiceSupervisorEvaluation) }}">
                    Consultar resultado
                </a>
            @else
                <p class="text-secondary">Evaluación terminal pendiente de envío.</p>
            @endif
        </section>

        <section class="col-md-6" aria-labelledby="tutor-evaluation-heading">
            <h2 id="tutor-evaluation-heading" class="h5">Evaluación terminal del Tutor</h2>
            @if ($practiceRequest->practiceEvaluation?->status === \App\Enums\PracticeEvaluationStatus::Submitted)
                <p>Evaluación enviada · Resultado: {{ number_format($practiceRequest->practiceEvaluation->score_total, 2) }}/100</p>
                <a href="{{ route($isCoordinator ? 'coordinacion.practice-evaluations.show' : 'alumno.practice-evaluations.show', $practiceRequest->practiceEvaluation) }}">
                    Consultar resultado
                </a>
            @else
                <p class="text-secondary">Evaluación terminal pendiente de envío.</p>
            @endif
        </section>
    </div>
</section>
