<section>
    <h1 class="h3 mb-3">
        {{ $letter->status === \App\Enums\PracticeTerminationLetterStatus::PendingReview ? 'Revisar carta de término' : 'Carta de término' }}
    </h1>

    <div class="card mb-4">
        <div class="card-body">
            <p class="mb-1"><strong>Alumno:</strong> {{ $letter->practiceRequest->student->name }}</p>
            <p class="mb-1"><strong>Empresa:</strong> {{ $letter->practiceRequest->company->name }}</p>
            <p class="mb-1"><strong>Archivo:</strong> {{ $letter->original_name }}</p>
            <p class="mb-2"><strong>Estado:</strong> {{ str($letter->status->value)->replace('_', ' ')->title() }}</p>
            <a class="btn btn-outline-primary" href="{{ route('coordinacion.practice-termination-letters.file', $letter) }}">Descargar carta para revisión</a>
        </div>
    </div>

    @if ($letter->status === \App\Enums\PracticeTerminationLetterStatus::PendingReview)
        @error('request') <div class="alert alert-warning" role="alert">{{ $message }}</div> @enderror
        @error('reviewChecklistConfirmed') <div class="alert alert-danger" role="alert">{{ $message }}</div> @enderror
        @error('reviewComment') <div class="alert alert-danger" role="alert">{{ $message }}</div> @enderror
        <div class="card card-body">
            <h2 class="h5">Verifica que la carta incluya</h2>
            <ul>
                <li>Nombre del alumno y empresa.</li>
                <li>Periodo de práctica y actividades.</li>
                <li>Conclusión de 240 horas y fecha.</li>
                <li>Firma del responsable empresarial.</li>
            </ul>
            <label class="form-label" for="reviewComment">Comentario (obligatorio si devuelves)</label>
            <textarea class="form-control @error('reviewComment') is-invalid @enderror" id="reviewComment" wire:model="reviewComment" rows="3" maxlength="2000"></textarea>
            <div class="form-check mt-3">
                <input class="form-check-input @error('reviewChecklistConfirmed') is-invalid @enderror" id="reviewChecklistConfirmed" type="checkbox" wire:model="reviewChecklistConfirmed">
                <label class="form-check-label" for="reviewChecklistConfirmed">Confirmo que revisé los elementos de contenido anteriores en el documento.</label>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button class="btn btn-success" type="button" wire:click="approve">Aceptar carta</button>
                <button class="btn btn-outline-warning" type="button" wire:click="returnForChanges">Devolver para corrección</button>
                <a class="btn btn-outline-secondary" href="{{ route('coordinacion.practice-termination-letters.index') }}">Volver</a>
            </div>
        </div>
    @else
        <div class="alert alert-secondary" role="status">
            Esta carta ya fue revisada; el registro es de solo lectura.
            @if ($letter->review_comment) <p class="mb-0 mt-2">Comentario: {{ $letter->review_comment }}</p> @endif
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('coordinacion.practice-termination-letters.index') }}">Volver</a>
    @endif
</section>
