<section>
    <div class="d-print-none mb-3 d-flex flex-wrap gap-2">
        <button class="btn btn-primary" type="button" onclick="window.print()">Imprimir para firma</button>
        <a class="btn btn-outline-secondary" href="{{ route('coordinacion.practice-requests.index') }}">Volver a solicitudes</a>
    </div>

    <article class="letter-print mx-auto">
        <div class="text-center">
            <strong>UNIVERSIDAD DE COLIMA</strong><br>
            FACULTAD DE TURISMO
        </div>
        <p class="text-end mt-4">Villa de Álvarez, Col., a {{ $printDate }}.</p>
        <p class="text-end"><strong>Folio: {{ $letter->folio }}</strong></p>
        <p><strong>Asunto:</strong> Carta de presentación.</p>
        <p class="mb-1">{{ $letter->letter_recipient }}</p>
        <p class="mb-1">{{ $letter->position }}</p>
        <p class="mb-1">{{ $letter->practiceRequest->company->name }}</p>
        <p class="mb-4">{{ $letter->practice_location }}</p>

        <p class="text-justify">
            Por este conducto me permito presentar a usted al C. {{ $letter->practiceRequest->student->name }},
            estudiante de {{ $letter->practiceRequest->grade_group }}, de la carrera Licenciatura en Gestión Turística,
            con número de cuenta {{ $formattedAccountNumber }}, a quien se le autoriza para realizar sus prácticas
            profesionales operativas y de mandos medios, a partir del {{ $letter->start_date->format('d/m/Y') }}
            y hasta el {{ $letter->end_date->format('d/m/Y') }}, en las actividades y turno que le sean encomendados,
            cumpliendo un total de 240 horas.
        </p>
        <p><strong>No. de IMSS:</strong> {{ $letter->practiceRequest->nss }}</p>
        <p>Sin otro en particular, aprovecho la ocasión para saludarle afectuosamente.</p>
        <p class="mt-4 mb-1"><strong>ATENTAMENTE</strong></p>
        <p>ESTUDIA*LUCHA*TRABAJA*</p>
        <div class="text-center mt-5 pt-4">
            <div class="signature-line"></div>
            Coordinación de prácticas<br>
            Facultad de Turismo
        </div>
        <p class="mt-5">c.p. Archivo</p>
    </article>
</section>

<style>
    .letter-print { max-width: 850px; padding: 2.5rem 3rem; background: #fff; color: #111; line-height: 1.65; }
    .signature-line { width: 280px; margin: 0 auto .5rem; border-top: 1px solid #111; }
    @media print {
        @page { size: letter; margin: 2cm; }
        body * { visibility: hidden; }
        .letter-print, .letter-print * { visibility: visible; }
        .letter-print { position: absolute; inset: 0; margin: 0 !important; padding: 0 !important; max-width: none; }
    }
</style>
