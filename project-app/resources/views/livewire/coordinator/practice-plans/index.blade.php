<section>
    <h1 class="h3 mb-3">Planes de trabajo</h1>
    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    @forelse ($versions as $version)
        <article class="card mb-3" wire:key="plan-version-{{ $version->id }}">
            <div class="card-body">
                <h2 class="h5">Versión {{ $version->version }} — {{ $version->status->value }}</h2>
                <p>Alumno: {{ $version->practicePlan->practiceRequest->student->name }}</p>
                <p>Empresa: {{ $version->practicePlan->practiceRequest->company->name }}</p>
                @if ($version->status === \App\Enums\PracticePlanStatus::PendingCoordinatorReview)
                    <a class="btn btn-primary" href="{{ route('coordinacion.practice-plans.review', $version) }}">Revisar plan</a>
                @else
                    <a class="btn btn-outline-secondary" href="{{ route('coordinacion.practice-plans.review', $version) }}">Consultar</a>
                @endif
            </div>
        </article>
    @empty
        <div class="alert alert-info">No hay planes de trabajo registrados.</div>
    @endforelse
</section>
