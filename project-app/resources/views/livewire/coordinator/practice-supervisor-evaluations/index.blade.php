<section>
    <h1 class="h3 mb-3">Evaluaciones finales del supervisor</h1>
    @forelse ($evaluations as $evaluation)
        <article class="card mb-3">
            <div class="card-body">
                <h2 class="h5">{{ $evaluation->practiceRequest->student->name }} — {{ $evaluation->score_total }}/100</h2>
                <p>Empresa: {{ $evaluation->practiceRequest->company->name }} · Supervisor: {{ $evaluation->supervisor->name }}</p>
                <a class="btn btn-outline-primary" href="{{ route('coordinacion.practice-supervisor-evaluations.show', $evaluation) }}">Consultar detalle</a>
            </div>
        </article>
    @empty
        <div class="alert alert-info">Aún no hay evaluaciones enviadas.</div>
    @endforelse
</section>
