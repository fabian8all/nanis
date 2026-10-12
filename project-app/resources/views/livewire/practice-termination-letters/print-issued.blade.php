<section>
    <div class="d-print-none mb-3 d-flex flex-wrap gap-2">
        <button class="btn btn-primary" type="button" onclick="window.print()">Imprimir carta de término</button>
        <a class="btn btn-outline-secondary" href="{{ $returnUrl }}">Volver al expediente</a>
    </div>

    <article class="termination-letter-print mx-auto">
        <header class="text-center mb-5">
            <strong>DIRECCIÓN GENERAL DE SERVICIO SOCIAL Y PRÁCTICA PROFESIONAL</strong>
            <h1 class="h4 mt-4">Carta de Terminación de Práctica Profesional</h1>
        </header>

        <p class="text-end">{{ $practiceRequest->practice_location }}, a {{ $printDate }}</p>

        <p>Por medio de la presente, quien suscribe, responsable de la Práctica Profesional de:</p>
        <p class="text-center"><strong>{{ $practiceRequest->company->name }}</strong></p>

        <p>Hace constar que:</p>
        <p class="text-center"><strong>{{ $practiceRequest->student->name }}</strong></p>

        <p>
            Estudiante de la Facultad de Turismo, de la carrera de Licenciatura en Gestión Turística,
            ha cumplido satisfactoriamente con las actividades asignadas para la prestación de la
            Práctica Profesional en el proyecto:
        </p>
        <p class="text-center">____________________________________________________________</p>

        <p>
            en el periodo comprendido del {{ $practiceRequest->start_date->format('d/m/Y') }}
            al {{ $practiceRequest->end_date->format('d/m/Y') }}, acumulando un total de 240 horas.
        </p>

        <p>
            La presente se extiende a petición de la persona interesada para los fines legales que
            convengan, en {{ $practiceRequest->practice_location }}, a {{ $printDate }}.
        </p>

        <p class="mt-5">Atentamente</p>

        <div class="text-center mt-5 pt-4">
            <div class="signature-line"></div>
            Nombre y firma de la persona responsable en la unidad receptora<br>
            Cargo
        </div>
        <p class="text-center mt-4">Sello de la unidad receptora</p>
        <p class="text-end small mt-5">Carta emitida por Coordinación el {{ $issuance->issued_at->format('d/m/Y H:i') }}</p>
    </article>
</section>

<style>
    .termination-letter-print { max-width: 850px; padding: 2.5rem 3rem; background: #fff; color: #111; line-height: 1.7; }
    .signature-line { width: 320px; margin: 0 auto .5rem; border-top: 1px solid #111; }
    @media print {
        @page { size: letter; margin: 2cm; }
        body * { visibility: hidden; }
        .termination-letter-print, .termination-letter-print * { visibility: visible; }
        .termination-letter-print { position: absolute; inset: 0; margin: 0 !important; padding: 0 !important; max-width: none; }
    }
</style>
