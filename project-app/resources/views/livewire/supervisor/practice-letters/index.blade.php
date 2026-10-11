<section>
    <h1 class="h3 mb-2">Cartas pendientes de aceptación</h1>
    <p class="text-secondary">Solo se muestran cartas emitidas para {{ auth()->user()->company->name }}.</p>

    @forelse ($letters as $letter)
        @php($request = $letter->practiceRequest)
        @php($latestSubmission = $request->latestAcceptanceSubmission)
        <article class="card mb-3" wire:key="practice-letter-{{ $letter->id }}">
            <div class="card-body">
                <h2 class="h5">{{ $letter->folio }}</h2>
                <p class="mb-2">Alumno: {{ $request->student->name }}</p>
                @if ($latestSubmission?->status === \App\Enums\PracticeAcceptanceStatus::PendingReview)
                    <p class="text-info mb-2">Carta de aceptación en revisión por Coordinación (versión {{ $latestSubmission->version }}).</p>
                @elseif ($latestSubmission?->status === \App\Enums\PracticeAcceptanceStatus::Returned)
                    <p class="text-warning mb-2">Coordinación devolvió la carta para corrección (versión {{ $latestSubmission->version }}).</p>
                    @if ($latestSubmission->review_comment)
                        <p class="mb-2">Comentario: {{ $latestSubmission->review_comment }}</p>
                    @endif
                @else
                    <p class="text-secondary mb-2">Pendiente de aceptación de la empresa.</p>
                @endif
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('supervisor.practice-letters.show', $letter) }}">Consultar carta de presentación</a>
                    @if (! $latestSubmission || $latestSubmission->status === \App\Enums\PracticeAcceptanceStatus::Returned)
                        <a class="btn btn-primary btn-sm" href="{{ route('supervisor.practice-acceptances.upload', $letter) }}">Cargar carta de aceptación</a>
                    @endif
                </div>
                @if ($request->acceptanceSubmissions->isNotEmpty())
                    <details class="mt-3">
                        <summary>Historial de envíos ({{ $request->acceptanceSubmissions->count() }})</summary>
                        <ul class="mt-2 mb-0">
                            @foreach ($request->acceptanceSubmissions as $submission)
                                <li>
                                    Versión {{ $submission->version }} — {{ $submission->status->value }}
                                    — <a href="{{ route('supervisor.practice-acceptances.file', $submission) }}">Descargar archivo</a>
                                    @if ($submission->review_comment)
                                        <br>Comentario de Coordinación: {{ $submission->review_comment }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </details>
                @endif
            </div>
        </article>
    @empty
        <div class="alert alert-info" role="status">No hay cartas de presentación pendientes de aceptación para esta empresa.</div>
    @endforelse
</section>
