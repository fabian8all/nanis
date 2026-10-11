<section>
    <h1 class="h3 mb-3">Cargar carta de aceptación</h1>
    <div class="card mb-4">
        <div class="card-body">
            <p class="mb-1"><strong>Folio:</strong> {{ $letter->folio }}</p>
            <p class="mb-1"><strong>Alumno:</strong> {{ $letter->practiceRequest->student->name }}</p>
            <p class="mb-0"><strong>Empresa:</strong> {{ $letter->practiceRequest->company->name }}</p>
        </div>
    </div>

    @if ($latestSubmission?->review_comment)
        <div class="alert alert-warning" role="status">
            Coordinación devolvió la versión {{ $latestSubmission->version }}: {{ $latestSubmission->review_comment }}
        </div>
    @endif

    <form wire:submit="submit" class="card card-body">
        <label class="form-label" for="acceptanceFile">Archivo (PDF, JPG o PNG; máximo 20 MB)</label>
        <input
            class="form-control @error('acceptanceFile') is-invalid @enderror"
            id="acceptanceFile"
            type="file"
            wire:model="acceptanceFile"
            accept=".pdf,.jpg,.jpeg,.png"
            required
        >
        @error('acceptanceFile') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div wire:loading wire:target="acceptanceFile" class="form-text">Cargando archivo…</div>
        <div class="d-flex gap-2 mt-3">
            <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">Enviar a Coordinación para revisión</button>
            <a class="btn btn-outline-secondary" href="{{ route('supervisor.practice-letters.show', $letter) }}">Cancelar</a>
        </div>
    </form>
</section>
