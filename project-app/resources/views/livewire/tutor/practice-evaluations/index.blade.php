<section>
    <h1 class="h3 mb-3">Evaluaciones finales de tutor</h1>
    <div class="mb-3">
        <label class="form-label" for="tutorPracticeEvaluationSearch">Buscar prácticas</label>
        <input id="tutorPracticeEvaluationSearch" class="form-control" type="search" wire:model.live.debounce.300ms="search" placeholder="Alumno o empresa">
    </div>
    @forelse ($requests as $request)
        <article class="card mb-3">
            <div class="card-body">
                <h2 class="h5">{{ $request->student->name }}</h2>
                <p class="mb-1">Empresa: {{ $request->company->name }}</p>
                <p class="mb-1">Reporte aprobado: versión {{ $request->practiceReport->approvedVersion->version }}</p>
                @if ($request->practiceEvaluation?->isSubmitted())
                    <a class="btn btn-outline-primary" href="{{ route('tutor.practice-evaluations.show', $request->practiceEvaluation) }}">Consultar resultado</a>
                @else
                    <a class="btn btn-primary" href="{{ route('tutor.practice-evaluations.manage', $request) }}">
                        {{ $request->practiceEvaluation ? 'Continuar borrador' : 'Iniciar evaluación' }}
                    </a>
                @endif
            </div>
        </article>
    @empty
        <div class="alert alert-info">{{ trim($search) !== '' ? 'No hay prácticas que coincidan con la búsqueda.' : 'No hay reportes aprobados pendientes de evaluación.' }}</div>
    @endforelse
    {{ $requests->links() }}
</section>
