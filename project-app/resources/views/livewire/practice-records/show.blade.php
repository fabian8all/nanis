<section>
    <h1 class="h3 mb-3">Expediente de práctica</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h5">{{ $practiceRequest->student->name }} — {{ $practiceRequest->company->name }}</h2>
            <p class="mb-0">Periodo: {{ $practiceRequest->start_date->format('d/m/Y') }} al {{ $practiceRequest->end_date->format('d/m/Y') }}</p>
        </div>
    </div>

    @if ($isSupervisor)
        <p class="text-secondary">Vista limitada: solo se muestran los documentos de tu práctica asignada.</p>

        <section class="mb-4" aria-labelledby="supervisor-cover-letter-heading">
            <h2 id="supervisor-cover-letter-heading" class="h5">
                Carta de presentación
                <span class="badge text-bg-secondary" data-document-status="{{ $documentStatuses['presentation'] }}">{{ $documentStatuses['presentation'] }}</span>
            </h2>
            @if ($practiceRequest->letter)
                <a class="btn btn-outline-primary btn-sm" href="{{ route('supervisor.practice-letters.show', $practiceRequest->letter) }}">
                    Consultar carta de presentación
                </a>
            @else
                <p class="text-secondary">No hay carta de presentación disponible.</p>
            @endif
        </section>

        <section class="mb-4" aria-labelledby="supervisor-acceptance-heading">
            <h2 id="supervisor-acceptance-heading" class="h5">
                Carta de aceptación
                <span class="badge text-bg-secondary" data-document-status="{{ $documentStatuses['acceptance'] }}">{{ $documentStatuses['acceptance'] }}</span>
            </h2>
            @if ($practiceRequest->latestAcceptanceSubmission?->status === \App\Enums\PracticeAcceptanceStatus::Accepted)
                <a class="btn btn-outline-primary btn-sm" href="{{ route('supervisor.practice-acceptances.file', $practiceRequest->latestAcceptanceSubmission) }}">
                    Consultar carta de aceptación
                </a>
            @else
                <p class="text-secondary">No hay una carta de aceptación aprobada.</p>
            @endif
        </section>

        <section class="mb-4" aria-labelledby="supervisor-report-heading">
            <h2 id="supervisor-report-heading" class="h5">
                Reporte final del alumno
                <span class="badge text-bg-secondary" data-document-status="{{ $documentStatuses['supervisor_report'] }}">{{ $documentStatuses['supervisor_report'] }}</span>
            </h2>
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
                            <li><a href="{{ route('practice-reports.attachments.download', $attachment) }}">{{ $attachment->original_name }}</a></li>
                        @endforeach
                    </ul>
                @endif
            @else
                <p class="text-secondary">No hay un reporte final aprobado disponible.</p>
            @endif
        </section>

        <section aria-labelledby="supervisor-own-evaluation-heading">
            <h2 id="supervisor-own-evaluation-heading" class="h5">
                Tu evaluación
                <span class="badge text-bg-secondary" data-document-status="{{ $documentStatuses['supervisor_evaluation'] }}">{{ $documentStatuses['supervisor_evaluation'] }}</span>
            </h2>
            @if ($practiceRequest->practiceSupervisorEvaluation?->status === \App\Enums\PracticeEvaluationStatus::Submitted)
                <p>Evaluación enviada · Resultado: {{ number_format($practiceRequest->practiceSupervisorEvaluation->score_total, 2) }}/100</p>
                <a href="{{ route('supervisor.practice-supervisor-evaluations.show', $practiceRequest->practiceSupervisorEvaluation) }}">Consultar tu evaluación</a>
            @else
                <p class="text-secondary">Tu evaluación aún no está enviada.</p>
            @endif
        </section>
    @else
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

        <section class="mb-4" aria-labelledby="presentation-letter-heading">
            <h2 id="presentation-letter-heading" class="h5">
                Carta de presentación
                <span class="badge text-bg-secondary" data-document-status="{{ $documentStatuses['presentation'] }}">{{ $documentStatuses['presentation'] }}</span>
            </h2>
            @if ($practiceRequest->letter)
                @php($presentationRoute = $isCoordinator ? 'coordinacion.practice-letters.print' : ($userIsTutor ? 'tutor.practice-letters.show' : 'alumno.practice-letters.show'))
                <a class="btn btn-outline-primary btn-sm" href="{{ route($presentationRoute, $practiceRequest->letter) }}">
                    Consultar carta de presentación
                </a>
            @else
                <p class="text-secondary">No hay carta de presentación disponible.</p>
            @endif
        </section>

        <section class="mb-4" aria-labelledby="acceptance-letter-heading">
            <h2 id="acceptance-letter-heading" class="h5">
                Carta de aceptación
                <span class="badge text-bg-secondary" data-document-status="{{ $documentStatuses['acceptance'] }}">{{ $documentStatuses['acceptance'] }}</span>
            </h2>
            @if ($practiceRequest->latestAcceptanceSubmission?->status === \App\Enums\PracticeAcceptanceStatus::Accepted)
                @php($acceptanceRoute = $isCoordinator ? 'coordinacion.practice-acceptances.file' : ($userIsTutor ? 'tutor.practice-acceptances.file' : 'alumno.practice-acceptances.file'))
                <a class="btn btn-outline-primary btn-sm" href="{{ route($acceptanceRoute, $practiceRequest->latestAcceptanceSubmission) }}">
                    Consultar carta de aceptación
                </a>
            @else
                <p class="text-secondary">No hay una carta de aceptación aprobada.</p>
            @endif
        </section>

        <section class="mb-4" aria-labelledby="practice-plan-heading">
            <h2 id="practice-plan-heading" class="h5">
                Plan de trabajo
                <span class="badge text-bg-secondary" data-document-status="{{ $documentStatuses['plan'] }}">{{ $documentStatuses['plan'] }}</span>
            </h2>
            @if ($latestPlan)
                @if ($isStudent)
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('alumno.practice-plans.show', $practiceRequest) }}">
                        Consultar plan de trabajo
                    </a>
                @elseif ($isCoordinator)
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('coordinacion.practice-plans.review', $latestPlan) }}">
                        Consultar plan de trabajo
                    </a>
                @else
                    <p class="text-secondary">Estado del plan de trabajo: {{ $documentStatuses['plan'] }}.</p>
                @endif
            @else
                <p class="text-secondary">No hay un plan de trabajo entregado.</p>
                @if ($isStudent)
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('alumno.practice-plans.show', $practiceRequest) }}">
                        Registrar plan de trabajo
                    </a>
                @endif
            @endif
        </section>

        <section class="mb-4" aria-labelledby="approved-report-heading">
            <h2 id="approved-report-heading" class="h5">
                Reporte final aprobado
                <span class="badge text-bg-secondary" data-document-status="{{ $documentStatuses['report'] }}">{{ $documentStatuses['report'] }}</span>
            </h2>
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
                            <li><a href="{{ route('practice-reports.attachments.download', $attachment) }}">{{ $attachment->original_name }}</a></li>
                        @endforeach
                    </ul>
                @endif
            @else
                <p class="text-secondary">No hay un reporte final aprobado.</p>
                @if ($isStudent)
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('alumno.practice-reports.show', $practiceRequest) }}">
                        Consultar reporte final
                    </a>
                @endif
            @endif
        </section>

        <div class="row g-4">
            <section class="col-md-6" aria-labelledby="supervisor-evaluation-heading">
                <h2 id="supervisor-evaluation-heading" class="h5">
                    Evaluación terminal del Supervisor
                    <span class="badge text-bg-secondary" data-document-status="{{ $documentStatuses['supervisor_evaluation'] }}">{{ $documentStatuses['supervisor_evaluation'] }}</span>
                </h2>
                @if ($practiceRequest->practiceSupervisorEvaluation?->status === \App\Enums\PracticeEvaluationStatus::Submitted)
                    <p>Evaluación enviada · Resultado: {{ number_format($practiceRequest->practiceSupervisorEvaluation->score_total, 2) }}/100</p>
                    <a href="{{ route($isCoordinator ? 'coordinacion.practice-supervisor-evaluations.show' : ($userIsTutor ? 'tutor.practice-supervisor-evaluations.show' : 'alumno.practice-supervisor-evaluations.show'), $practiceRequest->practiceSupervisorEvaluation) }}">
                        Consultar resultado
                    </a>
                @else
                    <p class="text-secondary">Evaluación terminal pendiente de envío.</p>
                @endif
            </section>

            <section class="col-md-6" aria-labelledby="tutor-evaluation-heading">
                <h2 id="tutor-evaluation-heading" class="h5">
                    Evaluación terminal del Tutor
                    <span class="badge text-bg-secondary" data-document-status="{{ $documentStatuses['tutor_evaluation'] }}">{{ $documentStatuses['tutor_evaluation'] }}</span>
                </h2>
                @if ($practiceRequest->practiceEvaluation?->status === \App\Enums\PracticeEvaluationStatus::Submitted)
                    <p>Evaluación enviada · Resultado: {{ number_format($practiceRequest->practiceEvaluation->score_total, 2) }}/100</p>
                    <a href="{{ route($isCoordinator ? 'coordinacion.practice-evaluations.show' : ($userIsTutor ? 'tutor.practice-evaluations.show' : 'alumno.practice-evaluations.show'), $practiceRequest->practiceEvaluation) }}">
                        Consultar resultado
                    </a>
                @else
                    <p class="text-secondary">Evaluación terminal pendiente de envío.</p>
                @endif
            </section>
        </div>

        <section class="mb-4" aria-labelledby="termination-letter-heading">
            <h2 id="termination-letter-heading" class="h5">
                Carta de término
                <span class="badge text-bg-secondary" data-document-status="{{ $documentStatuses['termination_letter'] }}">{{ $documentStatuses['termination_letter'] }}</span>
            </h2>
            @if ($practiceRequest->terminationLetterIssuance)
                <p>Coordinación emitió la carta de término.</p>
                <a class="btn btn-outline-primary btn-sm" href="{{ route('practice-termination-letters.print', $practiceRequest->terminationLetterIssuance) }}">
                    Consultar e imprimir carta de término
                </a>
            @else
                <p class="text-secondary">
                    @if ($canIssueLetter)
                        Los requisitos están listos para que Coordinación emita la carta.
                    @else
                        Coordinación emitirá la carta cuando el reporte esté aprobado y ambas evaluaciones se hayan enviado.
                    @endif
                </p>
                @if ($isCoordinator && $canIssueLetter)
                    @error('issuance') <div class="alert alert-danger" role="alert">{{ $message }}</div> @enderror
                    <button
                        class="btn btn-primary"
                        type="button"
                        wire:click="issueTerminationLetter"
                        wire:confirm="¿Emitir la carta de término e indicar el cierre del expediente?"
                        wire:loading.attr="disabled"
                    >
                        Emitir e imprimir carta de término
                    </button>
                @endif
            @endif
        </section>
    @endif
</section>
