<section>
    <h1 class="h3 mb-3">
        {{ $submission->status === \App\Enums\PracticeAcceptanceStatus::PendingReview ? 'Revisar carta de aceptación' : 'Historial de carta de aceptación' }}
    </h1>
    <div class="card mb-4">
        <div class="card-body">
            <p class="mb-1"><strong>Folio:</strong> {{ $submission->practiceRequest->letter->folio }}</p>
            <p class="mb-1"><strong>Alumno:</strong> {{ $submission->practiceRequest->student->name }}</p>
            <p class="mb-1"><strong>Empresa:</strong> {{ $submission->practiceRequest->company->name }}</p>
            <p class="mb-1"><strong>Supervisor:</strong> {{ $submission->supervisor->name }} ({{ $submission->supervisor->email }})</p>
            <p class="mb-2"><strong>Versión:</strong> {{ $submission->version }}</p>
            <a class="btn btn-outline-primary" href="{{ route('coordinacion.practice-acceptances.file', $submission) }}">Descargar carta de aceptación</a>
        </div>
    </div>

    <h2 class="h5">Historial de versiones</h2>
    <ul class="mb-4">
        @foreach ($history as $version)
            <li>
                Versión {{ $version->version }} — {{ $version->status->value }}
                @if ($version->review_comment) — {{ $version->review_comment }} @endif
                @if ($version->reviewer) — revisó {{ $version->reviewer->name }} @endif
            </li>
        @endforeach
    </ul>

    @if ($submission->status === \App\Enums\PracticeAcceptanceStatus::PendingReview)
        @error('request') <div class="alert alert-warning" role="alert">{{ $message }}</div> @enderror
        @error('reviewComment') <div class="alert alert-danger" role="alert">{{ $message }}</div> @enderror
        <form wire:submit="approve" class="card card-body">
            <label class="form-label" for="reviewComment">Comentario (obligatorio si devuelves el archivo)</label>
            <textarea class="form-control @error('reviewComment') is-invalid @enderror" id="reviewComment" wire:model="reviewComment" rows="3" maxlength="2000"></textarea>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button class="btn btn-success" type="submit">Aprobar carta</button>
                <button class="btn btn-outline-warning" type="button" wire:click="returnForChanges">Devolver para corrección</button>
                <a class="btn btn-outline-secondary" href="{{ route('coordinacion.practice-acceptances.index') }}">Volver</a>
            </div>
        </form>
    @else
        <div class="alert alert-secondary" role="status">Esta versión ya fue revisada; el historial es de solo lectura.</div>
        <a class="btn btn-outline-secondary" href="{{ route('coordinacion.practice-acceptances.index') }}">Volver</a>
    @endif
</section>
