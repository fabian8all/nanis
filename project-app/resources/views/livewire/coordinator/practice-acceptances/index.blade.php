<section>
    <h1 class="h3 mb-3">Cartas de aceptación por revisar</h1>

    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    <div class="mb-3">
        <label class="form-label" for="pendingAcceptanceSearch">Buscar cartas pendientes</label>
        <input id="pendingAcceptanceSearch" class="form-control" type="search" wire:model.live.debounce.300ms="pendingSearch" placeholder="Folio, alumno, empresa o supervisor">
    </div>

    @forelse ($submissions as $submission)
        <article class="card mb-3" wire:key="acceptance-{{ $submission->id }}">
            <div class="card-body">
                <h2 class="h5">{{ $submission->practiceRequest->letter->folio }}</h2>
                <p class="mb-1">Alumno: {{ $submission->practiceRequest->student->name }}</p>
                <p class="mb-1">Empresa: {{ $submission->practiceRequest->company->name }}</p>
                <p class="mb-1">Supervisor: {{ $submission->supervisor->name }} ({{ $submission->supervisor->email }})</p>
                <p class="mb-2">Versión {{ $submission->version }} · enviada {{ $submission->submitted_at->format('d/m/Y H:i') }}</p>
                <a class="btn btn-primary btn-sm" href="{{ route('coordinacion.practice-acceptances.review', $submission) }}">Revisar</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('coordinacion.practice-acceptances.file', $submission) }}">Descargar archivo</a>
            </div>
        </article>
    @empty
        <div class="alert alert-info" role="status">{{ trim($pendingSearch) !== '' ? 'No hay cartas pendientes que coincidan con la búsqueda.' : 'No hay cartas de aceptación pendientes de revisión.' }}</div>
    @endforelse
    {{ $submissions->links() }}

    <h2 class="h4 mt-5 mb-3">Historial de cartas revisadas</h2>
    <div class="mb-3">
        <label class="form-label" for="acceptanceHistorySearch">Buscar en el historial</label>
        <input id="acceptanceHistorySearch" class="form-control" type="search" wire:model.live.debounce.300ms="historySearch" placeholder="Folio, alumno, empresa o supervisor">
    </div>
    @forelse ($history as $submission)
        <article class="card mb-3" wire:key="acceptance-history-{{ $submission->id }}">
            <div class="card-body">
                <h3 class="h5">{{ $submission->practiceRequest->letter->folio }} — versión {{ $submission->version }}</h3>
                <p class="mb-1">Alumno: {{ $submission->practiceRequest->student->name }}</p>
                <p class="mb-1">Empresa: {{ $submission->practiceRequest->company->name }}</p>
                <p class="mb-2">Estado: {{ $submission->status->label() }} · revisada {{ $submission->reviewed_at->format('d/m/Y H:i') }}</p>
                @if ($submission->review_comment)
                    <p>Comentario: {{ $submission->review_comment }}</p>
                @endif
                <a class="btn btn-outline-primary btn-sm" href="{{ route('coordinacion.practice-acceptances.review', $submission) }}">Ver historial</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('coordinacion.practice-acceptances.file', $submission) }}">Descargar archivo</a>
            </div>
        </article>
    @empty
        <div class="alert alert-secondary" role="status">{{ trim($historySearch) !== '' ? 'No hay cartas revisadas que coincidan con la búsqueda.' : 'Aún no hay cartas revisadas.' }}</div>
    @endforelse
    {{ $history->links() }}
</section>
