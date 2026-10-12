<section>
    <h1 class="h3 mb-3">Encuestas de satisfacción pendientes</h1>
    @if (session('status')) <div class="alert alert-success" role="status">{{ session('status') }}</div> @endif

    @forelse ($requests as $surveyRequest)
        <article class="card mb-3">
            <div class="card-body">
                <h2 class="h5">{{ $surveyRequest->practiceRequest->company->name }}</h2>
                <p>Práctica de {{ $surveyRequest->practiceRequest->student->name }} · Solicitada el {{ $surveyRequest->requested_at->format('d/m/Y') }}</p>
                <a class="btn btn-primary" href="{{ route('supervisor.practice-surveys.answer', $surveyRequest) }}">Responder encuesta</a>
            </div>
        </article>
    @empty
        <div class="alert alert-info">No tienes encuestas de satisfacción pendientes.</div>
    @endforelse
</section>
