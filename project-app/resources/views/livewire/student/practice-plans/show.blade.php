<section>
    <h1 class="h3 mb-3">Plan de trabajo</h1>
    <p>Práctica en {{ $request->company->name }}</p>

    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    @if ($latestVersion?->status === \App\Enums\PracticePlanStatus::PendingSupervisorReview)
        <div class="alert alert-info" role="status">Tu plan está pendiente de revisión por el supervisor.</div>
    @elseif ($latestVersion?->status === \App\Enums\PracticePlanStatus::PendingCoordinatorReview)
        <div class="alert alert-info" role="status">El supervisor aprobó tu plan; está pendiente de revisión por Coordinación.</div>
    @elseif ($latestVersion?->status === \App\Enums\PracticePlanStatus::Approved)
        <div class="alert alert-success" role="status">Coordinación aprobó tu plan de trabajo.</div>
        @if ($request->practiceSupervisorEvaluation?->isSubmitted())
            <a class="btn btn-outline-primary" href="{{ route('alumno.practice-supervisor-evaluations.show', $request->practiceSupervisorEvaluation) }}">Consultar evaluación final del supervisor</a>
        @endif
    @elseif ($latestVersion?->status === \App\Enums\PracticePlanStatus::ReturnedBySupervisor)
        <div class="alert alert-warning" role="alert">El supervisor devolvió el plan: {{ $latestVersion->supervisor_review_comment }}</div>
    @elseif ($latestVersion?->status === \App\Enums\PracticePlanStatus::ReturnedByCoordinator)
        <div class="alert alert-warning" role="alert">Coordinación devolvió el plan: {{ $latestVersion->coordinator_review_comment }}</div>
    @endif

    @if ($plan)
        <h2 class="h5 mt-4">Historial</h2>
        @foreach ($plan->versions->sortBy('version') as $version)
            <article class="card mb-3">
                <div class="card-body">
                    <h3 class="h6">Versión {{ $version->version }} — {{ $version->status->value }}</h3>
                    <ul>
                        @foreach ($version->activities as $activity)
                            <li>
                                <strong>{{ $activity->description }}</strong>:
                                {{ $activity->start_date->format('d/m/Y') }}–{{ $activity->end_date->format('d/m/Y') }}.
                                Responsable: {{ $activity->responsible }}.
                                Resultado: {{ $activity->expected_result }}
                            </li>
                        @endforeach
                    </ul>
                    @if ($version->supervisor_review_comment)
                        <p>Comentario del supervisor: {{ $version->supervisor_review_comment }}</p>
                    @endif
                    @if ($version->coordinator_review_comment)
                        <p>Comentario de Coordinación: {{ $version->coordinator_review_comment }}</p>
                    @endif
                </div>
            </article>
        @endforeach
    @endif

    @if (! $latestVersion || in_array($latestVersion->status, [
        \App\Enums\PracticePlanStatus::ReturnedBySupervisor,
        \App\Enums\PracticePlanStatus::ReturnedByCoordinator,
    ], true))
        <h2 class="h5 mt-4">{{ $latestVersion ? 'Corregir y reenviar' : 'Nueva versión' }}</h2>
        @error('activities') <div class="alert alert-danger" role="alert">{{ $message }}</div> @enderror
        <form wire:submit="submit">
            @foreach ($activities as $index => $activity)
                <fieldset class="border rounded p-3 mb-3" wire:key="activity-{{ $index }}">
                    <legend class="float-none w-auto px-2 h6">Actividad {{ $index + 1 }}</legend>
                    <div class="mb-3">
                        <label class="form-label" for="description-{{ $index }}">Actividad</label>
                        <textarea class="form-control @error('activities.'.$index.'.description') is-invalid @enderror" id="description-{{ $index }}" wire:model="activities.{{ $index }}.description" required></textarea>
                        @error('activities.'.$index.'.description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="responsible-{{ $index }}">Responsable en la empresa</label>
                        <input class="form-control @error('activities.'.$index.'.responsible') is-invalid @enderror" id="responsible-{{ $index }}" wire:model="activities.{{ $index }}.responsible" maxlength="255" required>
                        @error('activities.'.$index.'.responsible') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="start-{{ $index }}">Inicio</label>
                            <input class="form-control @error('activities.'.$index.'.start_date') is-invalid @enderror" id="start-{{ $index }}" type="date" wire:model="activities.{{ $index }}.start_date" required>
                            @error('activities.'.$index.'.start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="end-{{ $index }}">Término</label>
                            <input class="form-control @error('activities.'.$index.'.end_date') is-invalid @enderror" id="end-{{ $index }}" type="date" wire:model="activities.{{ $index }}.end_date" required>
                            @error('activities.'.$index.'.end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="result-{{ $index }}">Resultado esperado</label>
                        <textarea class="form-control @error('activities.'.$index.'.expected_result') is-invalid @enderror" id="result-{{ $index }}" wire:model="activities.{{ $index }}.expected_result" required></textarea>
                        @error('activities.'.$index.'.expected_result') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    @if (count($activities) > 1)
                        <button class="btn btn-outline-danger btn-sm" type="button" wire:click="removeActivity({{ $index }})">Quitar actividad</button>
                    @endif
                </fieldset>
            @endforeach
            <button class="btn btn-outline-secondary" type="button" wire:click="addActivity">Agregar actividad</button>
            <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">Enviar al supervisor</button>
        </form>
    @endif
</section>
