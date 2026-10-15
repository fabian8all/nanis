<section>
    <h1 class="h3 mb-3">Encuestas de satisfacción</h1>
    @if (session('status')) <div class="alert alert-success" role="status">{{ session('status') }}</div> @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <section class="card mb-4" aria-labelledby="survey-configuration-heading">
        <div class="card-body">
            <h2 id="survey-configuration-heading" class="h5">Cuestionario institucional</h2>
            <p>La escala de cada pregunta es de 1 a 5. Una versión publicada no se puede modificar; las nuevas solicitudes conservan la versión vigente al crearse.</p>

            <div class="mb-3">
                <label class="form-label" for="surveyVersionSearch">Buscar versiones</label>
                <input id="surveyVersionSearch" class="form-control" type="search" wire:model.live.debounce.300ms="versionsSearch" placeholder="Número de versión o estado">
            </div>
            <ul class="list-group mb-3">
                @forelse ($versions as $version)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Versión {{ $version->version }} — {{ $version->isPublished() ? 'Publicada' : 'Borrador' }}</span>
                        <button class="btn btn-link btn-sm" type="button" wire:click="selectVersion({{ $version->id }})">Consultar</button>
                    </li>
                @empty
                    <li class="list-group-item text-secondary">{{ trim($versionsSearch) !== '' ? 'No hay versiones que coincidan con la búsqueda.' : 'Aún no hay versiones del cuestionario.' }}</li>
                @endforelse
            </ul>
            {{ $versions->links() }}

            @if ($selectedVersion->isPublished())
                <h3 class="h6">Versión {{ $selectedVersion->version }} publicada</h3>
                <ol>
                    @foreach ($selectedVersion->questions as $question)
                        <li>{{ $question->question }} (escala 1–5){{ $question->allow_comment ? ' · comentario opcional' : '' }}</li>
                    @endforeach
                </ol>
                <button class="btn btn-outline-primary" type="button" wire:click="addDraft">Crear siguiente borrador</button>
            @else
                <form wire:submit="saveQuestions">
                    <h3 class="h6">Editar borrador v{{ $selectedVersion->version }}</h3>
                    @forelse ($questions as $index => $question)
                        <fieldset class="border rounded p-3 mb-3" wire:key="survey-question-{{ $index }}">
                            <legend class="float-none w-auto fs-6">Pregunta {{ $index + 1 }}</legend>
                            <label class="form-label" for="question-{{ $index }}">Pregunta</label>
                            <textarea id="question-{{ $index }}" class="form-control mb-2" maxlength="500" wire:model="questions.{{ $index }}.question" required></textarea>
                            @error("questions.{$index}.question") <div class="text-danger small">{{ $message }}</div> @enderror
                            <div class="form-check mb-2">
                                <input id="comment-{{ $index }}" class="form-check-input" type="checkbox" wire:model="questions.{{ $index }}.allow_comment">
                                <label class="form-check-label" for="comment-{{ $index }}">Permitir comentario opcional</label>
                            </div>
                            <p class="form-text">Escala de respuesta fija: 1 a 5.</p>
                            <button class="btn btn-sm btn-outline-danger" type="button" wire:click="removeQuestion({{ $index }})">Quitar pregunta</button>
                        </fieldset>
                    @empty
                        <p class="alert alert-warning">Agrega al menos una pregunta para publicar el cuestionario.</p>
                    @endforelse
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary" type="button" wire:click="addQuestion">Agregar pregunta</button>
                        <button class="btn btn-primary" type="submit">Guardar borrador</button>
                        <button class="btn btn-success" type="button" wire:click="publish">Validar y publicar</button>
                    </div>
                </form>
            @endif
        </div>
    </section>

    <section class="mb-4" aria-labelledby="request-survey-heading">
        <h2 id="request-survey-heading" class="h5">Solicitar una encuesta</h2>
        @if (! $publishedVersion)
            <div class="alert alert-info">Publica el cuestionario institucional antes de solicitar respuestas.</div>
        @else
            <div class="mb-3">
                <label class="form-label" for="eligiblePracticeSearch">Buscar prácticas elegibles</label>
                <input id="eligiblePracticeSearch" class="form-control" type="search" wire:model.live.debounce.300ms="eligibleSearch" placeholder="Alumno, empresa o supervisor">
            </div>
            @if ($eligiblePractices->isEmpty())
                <div class="alert alert-info">{{ trim($eligibleSearch) !== '' ? 'No hay prácticas elegibles que coincidan con la búsqueda.' : 'No hay prácticas con aceptación vigente sin solicitud de encuesta.' }}</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead><tr><th>Alumno</th><th>Empresa</th><th>Supervisor directo</th><th><span class="visually-hidden">Acción</span></th></tr></thead>
                        <tbody>
                            @foreach ($eligiblePractices as $practice)
                                <tr wire:key="eligible-practice-{{ $practice->id }}">
                                    <td>{{ $practice->student->name }}</td>
                                    <td>{{ $practice->company->name }}</td>
                                    <td>{{ $practice->latestAcceptanceSubmission->supervisor->name }}</td>
                                    <td><button class="btn btn-sm btn-outline-primary" type="button" wire:click="requestSurvey({{ $practice->id }})">Solicitar encuesta</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $eligiblePractices->links() }}
            @endif
        @endif
    </section>

    <section aria-labelledby="survey-results-heading">
        <h2 id="survey-results-heading" class="h5">Solicitudes y respuestas</h2>
        <div class="mb-3">
            <label class="form-label" for="surveyRequestSearch">Buscar solicitudes y respuestas</label>
            <input id="surveyRequestSearch" class="form-control" type="search" wire:model.live.debounce.300ms="surveyRequestsSearch" placeholder="Alumno, empresa o supervisor">
        </div>
        @forelse ($surveyRequests as $surveyRequest)
            <article class="card mb-3" wire:key="survey-request-{{ $surveyRequest->id }}">
                <div class="card-header d-flex flex-wrap justify-content-between gap-2">
                    <span>{{ $surveyRequest->practiceRequest->student->name }} — {{ $surveyRequest->practiceRequest->company->name }}</span>
                    <span>
                        @if ($surveyRequest->status === \App\Enums\PracticeSurveyRequestStatus::Requested)
                            Solicitada
                        @elseif ($surveyRequest->status === \App\Enums\PracticeSurveyRequestStatus::Responded)
                            Respondida
                        @else
                            Cancelada
                        @endif
                    </span>
                </div>
                <div class="card-body">
                    <p class="mb-2">Supervisor: {{ $surveyRequest->supervisor->name }} · Cuestionario v{{ $surveyRequest->version->version }}</p>
                    @if ($surveyRequest->status === \App\Enums\PracticeSurveyRequestStatus::Requested)
                        <button class="btn btn-sm btn-outline-danger" type="button" wire:click="cancelSurvey({{ $surveyRequest->id }})">Cancelar solicitud</button>
                    @elseif ($surveyRequest->status === \App\Enums\PracticeSurveyRequestStatus::Responded)
                        <p class="mb-2">Respondida el {{ $surveyRequest->responded_at->format('d/m/Y') }}</p>
                        <ol class="mb-0">
                            @foreach ($surveyRequest->answers as $answer)
                                <li class="mb-2">
                                    {{ $answer->question->question }} — {{ $answer->rating }} / 5
                                    @if ($answer->comment)
                                        <p class="mb-0 text-secondary">Comentario: {{ $answer->comment }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="mb-0">Cancelada el {{ $surveyRequest->cancelled_at->format('d/m/Y') }}</p>
                    @endif
                </div>
            </article>
        @empty
            <div class="alert alert-info">{{ trim($surveyRequestsSearch) !== '' ? 'No hay solicitudes que coincidan con la búsqueda.' : 'Todavía no hay solicitudes de encuesta.' }}</div>
        @endforelse
        {{ $surveyRequests->links() }}
    </section>
</section>
