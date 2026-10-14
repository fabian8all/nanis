<section>
    <h1 class="h3 mb-3">Revisar plan de trabajo — versión {{ $version->version }}</h1>
    <p>Alumno: {{ $version->practicePlan->practiceRequest->student->name }}</p>

    <h2 class="h5">Actividades</h2>
    @foreach ($version->activities as $activity)
        <article class="card mb-2">
            <div class="card-body">
                <h3 class="h6">{{ $activity->description }}</h3>
                <p>Fechas: {{ $activity->start_date->format('d/m/Y') }}–{{ $activity->end_date->format('d/m/Y') }}</p>
                <p>Responsable: {{ $activity->responsible }}</p>
                <p class="mb-0">Resultado esperado: {{ $activity->expected_result }}</p>
            </div>
        </article>
    @endforeach

    <h2 class="h5 mt-4">Historial</h2>
    @foreach ($history as $historicalVersion)
        <div class="border rounded p-3 mb-2">
            <strong>Versión {{ $historicalVersion->version }} — {{ $historicalVersion->status->label() }}</strong>
            @if ($historicalVersion->supervisor_review_comment)
                <p>Supervisor: {{ $historicalVersion->supervisor_review_comment }}</p>
            @endif
            @if ($historicalVersion->coordinator_review_comment)
                <p>Coordinación: {{ $historicalVersion->coordinator_review_comment }}</p>
            @endif
        </div>
    @endforeach

    @if ($version->status === \App\Enums\PracticePlanStatus::PendingSupervisorReview)
        @error('reviewComment') <div class="alert alert-danger">{{ $message }}</div> @enderror
        <form wire:submit="approve" class="card card-body mt-4">
            <label class="form-label" for="reviewComment">Comentario (obligatorio para devolver)</label>
            <textarea class="form-control" id="reviewComment" wire:model="reviewComment" maxlength="2000"></textarea>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-success" type="submit">Aprobar para Coordinación</button>
                <button class="btn btn-outline-warning" type="button" wire:click="returnForChanges">Devolver al alumno</button>
            </div>
        </form>
    @else
        <div class="alert alert-secondary">Esta versión no está pendiente de revisión por el supervisor.</div>
    @endif
</section>
