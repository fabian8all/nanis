<section>
    <h1 class="h3 mb-2">Reporte final de prácticas</h1>
    <p>Empresa: {{ $request->company->name }}</p>
    <p>Tutor asignado: {{ $request->tutor?->name ?? 'Pendiente de asignación por Coordinación' }}</p>
    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif
    @error('report') <div class="alert alert-warning" role="alert">{{ $message }}</div> @enderror
    @if ($latest?->status === \App\Enums\PracticeReportStatus::PendingTutorReview)
        <div class="alert alert-info">Tu reporte está pendiente de revisión por el tutor.</div>
    @elseif ($latest?->status === \App\Enums\PracticeReportStatus::Returned)
        <div class="alert alert-warning">El tutor devolvió el reporte: {{ $latest->review_comment }}</div>
    @elseif ($approved && $latest?->status === \App\Enums\PracticeReportStatus::Approved)
        <div class="alert alert-success">El reporte fue aprobado por el tutor. Está cerrado.</div>
    @endif

    @if ($approved)
        <h2 class="h5">Versión aprobada: {{ $approved->version }}</h2>
        @include('livewire.student.practice-reports.partials.version', ['version' => $approved])
        @if ($report->practiceRequest->practiceEvaluation?->isSubmitted())
            <a class="btn btn-outline-primary mt-2" href="{{ route('alumno.practice-evaluations.show', $report->practiceRequest->practiceEvaluation) }}">Consultar evaluación final del tutor</a>
        @endif
    @endif

    @if ($report)
        <h2 class="h5 mt-4">Historial de versiones</h2>
        @foreach ($report->versions->sortBy('version') as $version)
            <article class="card mb-3">
                <div class="card-body">
                    <h3 class="h6">Versión {{ $version->version }} — {{ $version->status->label() }}</h3>
                    @if ($version->review_comment)
                        <p>Comentario del tutor: {{ $version->review_comment }}</p>
                    @endif
                    @foreach ($version->section_comments ?? [] as $section => $comment)
                        @if ($comment)
                            <p>{{ ucfirst($section) }}: {{ $comment }}</p>
                        @endif
                    @endforeach
                    @foreach ($version->attachments as $attachment)
                        <a href="{{ route('practice-reports.attachments.download', $attachment) }}">{{ $attachment->original_name }}</a>
                    @endforeach
                    <details class="mt-2">
                        <summary>Consultar contenido de la versión</summary>
                        @include('livewire.student.practice-reports.partials.version', ['version' => $version])
                    </details>
                </div>
            </article>
        @endforeach
    @endif

    @if ($canEdit)
        <form wire:submit="saveDraft" class="mt-4">
            @foreach ([
                'area' => 'Área de la estancia',
                'period' => 'Periodo',
                'objectives' => 'Objetivos',
                'activities' => 'Actividades realizadas',
                'outcomes' => 'Resultados y aprendizajes',
                'conclusions' => 'Conclusiones',
                'recommendations' => 'Recomendaciones',
            ] as $field => $label)
                <div class="mb-3">
                    <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                    @if (in_array($field, ['area', 'period'], true))
                        <input class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" wire:model="{{ $field }}" maxlength="255" required>
                    @else
                        <textarea class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" wire:model="{{ $field }}" rows="4" maxlength="20000" required></textarea>
                    @endif
                    @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            @endforeach
            <div class="mb-3">
                <label class="form-label" for="attachments">Anexos (PDF, JPG o PNG; máximo 20 MB por archivo)</label>
                <input class="form-control @error('attachments.*') is-invalid @enderror" id="attachments" type="file" wire:model="attachments" multiple accept=".pdf,.jpg,.jpeg,.png">
                @error('attachments.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div wire:loading wire:target="attachments">Cargando anexos…</div>
            </div>
            @if ($latest?->attachments->isNotEmpty())
                <p>Anexos guardados:</p>
                <ul>
                    @foreach ($latest->attachments as $attachment)
                        <li><a href="{{ route('practice-reports.attachments.download', $attachment) }}">{{ $attachment->original_name }}</a></li>
                    @endforeach
                </ul>
            @endif
            <button class="btn btn-outline-secondary" type="submit" wire:loading.attr="disabled">Guardar borrador</button>
            <button class="btn btn-primary" type="button" wire:click="submit" wire:loading.attr="disabled">Enviar al tutor</button>
        </form>
    @endif
</section>
