<section>
    <h1 class="h3 mb-3">Carta de término</h1>
    <div class="card mb-4">
        <div class="card-body">
            <p class="mb-2"><strong>Empresa:</strong> {{ $practiceRequest->company->name }}</p>
            <p class="mb-0"><strong>Alumno:</strong> {{ $practiceRequest->student->name }}</p>
        </div>
    </div>

    <div class="alert alert-info">
        La empresa debe entregarte una carta firmada por su responsable. Coordinación verificará que incluya:
        <ul class="mb-0 mt-2">
            <li>Tu nombre y el de la empresa.</li>
            <li>El periodo de práctica y las actividades realizadas.</li>
            <li>La conclusión de 240 horas y la fecha.</li>
            <li>La firma del responsable empresarial.</li>
        </ul>
    </div>

    @if (session()->has('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    @if ($letter?->status === \App\Enums\PracticeTerminationLetterStatus::Accepted)
        <div class="alert alert-success" role="status">
            Coordinación aceptó tu carta de término.
        </div>
        <a class="btn btn-outline-primary" href="{{ route('alumno.practice-termination-letters.file', $letter) }}">Descargar carta aceptada</a>
    @else
        @if ($letter?->status === \App\Enums\PracticeTerminationLetterStatus::PendingReview)
            <div class="alert alert-info" role="status">Tu carta está pendiente de revisión de Coordinación.</div>
        @elseif ($letter?->status === \App\Enums\PracticeTerminationLetterStatus::Returned)
            <div class="alert alert-warning" role="status">
                Coordinación devolvió tu carta para corrección: {{ $letter->review_comment }}
            </div>
            <p>
                <a href="{{ route('alumno.practice-termination-letters.file', $letter) }}">Descargar carta enviada</a>
            </p>
        @endif

        @error('request') <div class="alert alert-warning" role="alert">{{ $message }}</div> @enderror
        <form wire:submit="submit" class="card card-body">
            <label class="form-label" for="letterFile">Archivo de la carta (PDF, JPG o PNG; máximo 20 MB)</label>
            <input
                class="form-control @error('letterFile') is-invalid @enderror"
                id="letterFile"
                type="file"
                wire:model="letterFile"
                accept=".pdf,.jpg,.jpeg,.png"
                required
            >
            @error('letterFile') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div wire:loading wire:target="letterFile" class="form-text">Cargando archivo…</div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">
                    {{ $letter?->status === \App\Enums\PracticeTerminationLetterStatus::Returned ? 'Corregir y reenviar' : 'Enviar a Coordinación' }}
                </button>
                <a class="btn btn-outline-secondary" href="{{ route('alumno.practice-request.show') }}">Volver a la solicitud</a>
            </div>
        </form>
    @endif
</section>
