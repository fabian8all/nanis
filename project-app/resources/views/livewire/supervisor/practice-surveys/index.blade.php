<section>
    <h1 class="h3 mb-3">Encuestas de satisfacción</h1>
    @if (session('status')) <div class="alert alert-success" role="status">{{ session('status') }}</div> @endif

    <div class="mb-3">
        <label class="form-label" for="supervisorSurveySearch">Buscar encuestas</label>
        <input id="supervisorSurveySearch" class="form-control" type="search" wire:model.live.debounce.300ms="search" placeholder="Alumno o empresa">
    </div>

    @forelse ($requests as $surveyRequest)
        <article class="card mb-3">
            <div class="card-body">
                <h2 class="h5">{{ $surveyRequest->practiceRequest->company->name }}</h2>
                <p>Práctica de {{ $surveyRequest->practiceRequest->student->name }} · Solicitada el {{ $surveyRequest->requested_at->format('d/m/Y') }}</p>
                @if ($surveyRequest->status === \App\Enums\PracticeSurveyRequestStatus::Responded)
                    <span class="badge text-bg-success">Respondida</span>
                    <a class="btn btn-outline-primary" href="{{ route('supervisor.practice-surveys.answer', $surveyRequest) }}">Consultar mi respuesta</a>
                @else
                    <span class="badge text-bg-warning">Pendiente</span>
                    <a class="btn btn-primary" href="{{ route('supervisor.practice-surveys.answer', $surveyRequest) }}">Responder encuesta</a>
                @endif
            </div>
        </article>
    @empty
        <div class="alert alert-info">{{ trim($search) !== '' ? 'No hay encuestas que coincidan con la búsqueda.' : 'No tienes encuestas de satisfacción solicitadas o respondidas.' }}</div>
    @endforelse
    {{ $requests->links() }}
</section>
