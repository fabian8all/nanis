<section>
    <div class="d-print-none">
        <h1 class="h3 mb-2">Solicitud de práctica</h1>

        @if (session()->has('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->has('company'))
            <div class="alert alert-warning" role="alert">{{ $errors->first('company') }}</div>
            <a class="btn btn-outline-primary mb-3" href="{{ route('alumno.companies.index') }}">Elegir empresa</a>
        @endif

        @if ($practiceRequest && $practiceRequest->status->value !== 'returned')
            <div class="alert alert-info" role="status">
                Tu solicitud está en trámite y no se puede editar. Coordinación te avisará si requiere correcciones.
            </div>
            <button class="btn btn-outline-primary" type="button" onclick="window.print()">Imprimir carta de no responsiva</button>
        @elseif ($selectedCompany)
            @if ($practiceRequest)
                <div class="alert alert-warning" role="status">Coordinación devolvió tu solicitud. Corrige los datos y vuelve a enviarla.</div>
            @endif
            <p class="text-secondary">Empresa seleccionada: <strong>{{ $selectedCompany->name }}</strong></p>

            @error('request') <div class="alert alert-warning" role="alert">{{ $message }}</div> @enderror

            <form wire:submit="submit" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="ucolEmail">Correo UCOL</label>
                    <input class="form-control @error('ucolEmail') is-invalid @enderror" id="ucolEmail" type="email" wire:model="ucolEmail" maxlength="255" required>
                    @error('ucolEmail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="gradeGroup">Grado y grupo</label>
                    <input class="form-control @error('gradeGroup') is-invalid @enderror" id="gradeGroup" type="text" wire:model="gradeGroup" maxlength="255" required>
                    @error('gradeGroup') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                <div class="col-md-6">
                    <label class="form-label" for="practiceLocation">Lugar de práctica</label>
                    <input class="form-control @error('practiceLocation') is-invalid @enderror" id="practiceLocation" type="text" wire:model="practiceLocation" maxlength="255" required>
                    @error('practiceLocation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="position">Puesto</label>
                    <input class="form-control @error('position') is-invalid @enderror" id="position" type="text" wire:model="position" maxlength="255" required>
                    @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="letterRecipient">Destinatario de la carta</label>
                    <input class="form-control @error('letterRecipient') is-invalid @enderror" id="letterRecipient" type="text" wire:model="letterRecipient" maxlength="255" required>
                    @error('letterRecipient') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input @error('acceptNonResponsiva') is-invalid @enderror" id="acceptNonResponsiva" type="checkbox" wire:model="acceptNonResponsiva" required>
                        <label class="form-check-label" for="acceptNonResponsiva">Acepto la carta de no responsiva, que imprimiré, completaré a mano y firmaré.</label>
                        @error('acceptNonResponsiva') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <button class="btn btn-link ps-0" type="button" onclick="window.print()">Ver e imprimir carta de no responsiva</button>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">{{ $practiceRequest ? 'Guardar correcciones y reenviar' : 'Enviar solicitud' }}</button>
                </div>
            </form>
        @else
            <div class="alert alert-info">Primero selecciona una empresa con convenio vigente.</div>
            <a class="btn btn-primary" href="{{ route('alumno.companies.index') }}">Consultar empresas</a>
        @endif
    </div>

    <article class="non-responsiva-print mt-4">
        <h1 class="h4 text-center">CARTA DE NO RESPONSIVA</h1>
        <p>A QUIEN CORRESPONDA:</p>
        <p>
            El que suscribe, __________________________, con el domicilio en:
            ________________________________________________________, en la ciudad de ____________________, _____________;
            alumno de 9no. semestre de la materia de Estancia Profesional, con número de cuenta: ______________
            Destino de la Estancia Profesional: ____________________ correspondiente a la FACULTAD DE TURISMO de la
            UNIVERSIDAD DE COLIMA, extiende la presente para declarar que reconoce que todo viaje conlleva riesgos de
            diferentes tipos y que, en caso de ocurrir algún tipo de accidente o situación imprevista que ocurra durante
            la estancia profesional durante los días del __________________________ al __________________________.
        </p>
        <p>De ninguna manera atribuiré culpa o responsabilidad alguna a la UNIVERSIDAD DE COLIMA, o a cualquiera de sus funcionarios y maestros.</p>
        <p class="text-end">Villa de Álvarez, Colima, a {{ $printDate }}.</p>
        <p class="mt-5">NOMBRE: ______________________________ &nbsp;&nbsp;&nbsp; FIRMA: __________________________</p>
    </article>
</section>

<style>
    @media screen {
        .non-responsiva-print { display: none; }
    }
    @media print {
        body * { visibility: hidden; }
        .non-responsiva-print, .non-responsiva-print * { visibility: visible; }
        .non-responsiva-print { position: absolute; inset: 0; margin: 2cm !important; }
        @page { margin: 1cm; }
    }
</style>
