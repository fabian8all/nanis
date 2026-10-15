<section>
    <h1 class="h3 mb-3">Evaluaciones finales asignadas</h1>
    <div class="mb-3">
        <label class="form-label" for="supervisorPracticeEvaluationSearch">Buscar prácticas</label>
        <input id="supervisorPracticeEvaluationSearch" class="form-control" type="search" wire:model.live.debounce.300ms="search" placeholder="Alumno o empresa">
    </div>
    @forelse ($requests as $request)
        <article class="card mb-3">
            <div class="card-body">
                <h2 class="h5">{{ $request->student->name }}</h2>
                <p>Empresa: {{ $request->company->name }}</p>
                <p>Plan aprobado: versión {{ $request->practicePlan->latestVersion->version }}</p>
                @if ($request->practiceSupervisorEvaluation?->isSubmitted())
                    <a class="btn btn-outline-primary" href="{{ route('supervisor.practice-supervisor-evaluations.show', $request->practiceSupervisorEvaluation) }}">Consultar resultado</a>
                @else
                    <a class="btn btn-primary" href="{{ route('supervisor.practice-supervisor-evaluations.manage', $request) }}">
                        {{ $request->practiceSupervisorEvaluation ? 'Continuar borrador' : 'Iniciar evaluación' }}
                    </a>
                @endif
            </div>
        </article>
    @empty
        <div class="alert alert-info">{{ trim($search) !== '' ? 'No hay prácticas que coincidan con la búsqueda.' : 'No hay prácticas con aceptación y plan aprobados para evaluar.' }}</div>
    @endforelse
    {{ $requests->links() }}
</section>
