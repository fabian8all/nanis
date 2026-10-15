<section>
    <h1 class="h3 mb-3">Evaluaciones finales del supervisor</h1>
    <div class="mb-3">
        <label class="form-label" for="supervisorEvaluationSearch">Buscar evaluaciones</label>
        <input id="supervisorEvaluationSearch" class="form-control" type="search" wire:model.live.debounce.300ms="search" placeholder="Alumno, empresa o supervisor">
    </div>
    @forelse ($evaluations as $evaluation)
        <article class="card mb-3">
            <div class="card-body">
                <h2 class="h5">{{ $evaluation->practiceRequest->student->name }} — {{ $evaluation->score_total }}/100</h2>
                <p>Empresa: {{ $evaluation->practiceRequest->company->name }} · Supervisor: {{ $evaluation->supervisor->name }}</p>
                <a class="btn btn-outline-primary" href="{{ route('coordinacion.practice-supervisor-evaluations.show', $evaluation) }}">Consultar detalle</a>
            </div>
        </article>
    @empty
        <div class="alert alert-info">{{ trim($search) !== '' ? 'No hay evaluaciones que coincidan con la búsqueda.' : 'Aún no hay evaluaciones enviadas.' }}</div>
    @endforelse
    {{ $evaluations->links() }}
</section>
