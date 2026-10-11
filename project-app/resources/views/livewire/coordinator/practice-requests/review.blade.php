<section>
    <a class="btn btn-link px-0" href="{{ route('coordinacion.practice-requests.index') }}">Volver a solicitudes</a>
    <h1 class="h3 mb-3">Revisar solicitud #{{ $practiceRequest->id }}</h1>

    @if ($errors->has('request'))
        <div class="alert alert-warning" role="alert">{{ $errors->first('request') }}</div>
    @endif

    @if ($practiceRequest->reviews->isNotEmpty())
        <section class="mb-4" aria-labelledby="review-history-title">
            <h2 class="h5" id="review-history-title">Historial de revisiones</h2>
            <ol class="list-group list-group-numbered">
                @foreach ($practiceRequest->reviews as $review)
                    <li class="list-group-item">
                        <strong>{{ $review->decision === \App\Enums\PracticeRequestStatus::Returned ? 'Devuelta' : 'Aprobada' }}</strong>
                        <span class="text-secondary"> · {{ $review->coordinator->name }} · {{ $review->reviewed_at->format('d/m/Y H:i') }}</span>
                        @if ($review->comment)
                            <p class="mb-0 mt-1">{{ $review->comment }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </section>
    @endif

    <section class="card mb-4" aria-labelledby="request-data-title">
        <div class="card-header" id="request-data-title">Datos originales de la solicitud</div>
        <dl class="card-body row mb-0">
            <dt class="col-sm-3">Alumno</dt><dd class="col-sm-9">{{ $practiceRequest->student->name }}</dd>
            <dt class="col-sm-3">Correo UCOL</dt><dd class="col-sm-9">{{ $practiceRequest->ucol_email }}</dd>
            <dt class="col-sm-3">Empresa</dt><dd class="col-sm-9">{{ $practiceRequest->company->name }}</dd>
            <dt class="col-sm-3">Grado/grupo</dt><dd class="col-sm-9">{{ $practiceRequest->grade_group }}</dd>
            <dt class="col-sm-3">NSS y cuenta</dt><dd class="col-sm-9">Datos protegidos; se incluirán solo en la carta impresa.</dd>
            <dt class="col-sm-3">Periodo</dt><dd class="col-sm-9">{{ $practiceRequest->start_date->format('d/m/Y') }} – {{ $practiceRequest->end_date->format('d/m/Y') }}</dd>
            <dt class="col-sm-3">Lugar solicitado</dt><dd class="col-sm-9">{{ $practiceRequest->practice_location }}</dd>
            <dt class="col-sm-3">Puesto solicitado</dt><dd class="col-sm-9">{{ $practiceRequest->position }}</dd>
            <dt class="col-sm-3">Destinatario</dt><dd class="col-sm-9">{{ $practiceRequest->letter_recipient }}</dd>
        </dl>
    </section>

    <section class="card mb-4" aria-labelledby="letter-data-title">
        <div class="card-header" id="letter-data-title">Datos de la carta de presentación</div>
        <div class="card-body">
            <p class="text-secondary">Puedes ajustar destinatario, puesto, lugar y fechas. Los cambios se guardarán solo en la carta, no en la solicitud del alumno.</p>
            <form wire:submit="approve" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="letterRecipient">Destinatario</label>
                    <input class="form-control @error('letterRecipient') is-invalid @enderror" id="letterRecipient" type="text" wire:model="letterRecipient" maxlength="255" required>
                    @error('letterRecipient') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="position">Puesto</label>
                    <input class="form-control @error('position') is-invalid @enderror" id="position" type="text" wire:model="position" maxlength="255" required>
                    @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="practiceLocation">Lugar</label>
                    <input class="form-control @error('practiceLocation') is-invalid @enderror" id="practiceLocation" type="text" wire:model="practiceLocation" maxlength="255" required>
                    @error('practiceLocation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="startDate">Fecha de inicio</label>
                    <input class="form-control @error('startDate') is-invalid @enderror" id="startDate" type="date" wire:model="startDate" required>
                    @error('startDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="endDate">Fecha de término</label>
                    <input class="form-control @error('endDate') is-invalid @enderror" id="endDate" type="date" wire:model="endDate" required>
                    @error('endDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="reviewComment">Comentario de revisión <span class="text-secondary">(obligatorio al devolver)</span></label>
                    <textarea class="form-control @error('reviewComment') is-invalid @enderror" id="reviewComment" wire:model="reviewComment" rows="3" maxlength="2000"></textarea>
                    @error('reviewComment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">Aprobar y preparar carta</button>
                    <button class="btn btn-outline-warning" type="button" wire:click="returnForChanges" wire:loading.attr="disabled">Devolver para corrección</button>
                </div>
            </form>
        </div>
    </section>
</section>
