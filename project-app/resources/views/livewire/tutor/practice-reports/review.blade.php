<section>
    <h1 class="h3">Reporte final — versión {{ $version->version }}</h1>
    <p>Alumno: {{ $version->report->practiceRequest->student->name }}</p>
    <p>Empresa: {{ $version->report->practiceRequest->company->name }}</p>
    @include('livewire.student.practice-reports.partials.version', ['version' => $version])
    <h2 class="h5">Anexos</h2>
    <ul>
        @foreach ($version->attachments as $attachment)
            <li><a href="{{ route('practice-reports.attachments.download', $attachment) }}">{{ $attachment->original_name }}</a></li>
        @endforeach
    </ul>
    <h2 class="h5">Historial</h2>
    @foreach ($history as $item)
        <article class="card mb-2">
            <div class="card-body">
                <h3 class="h6">Versión {{ $item->version }} — {{ $item->status->label() }}</h3>
                @include('livewire.student.practice-reports.partials.version', ['version' => $item])
                @if ($item->review_comment)
                    <p>Comentario general: {{ $item->review_comment }}</p>
                @endif
                @foreach ($item->section_comments ?? [] as $section => $comment)
                    @if ($comment)
                        <p>{{ ucfirst($section) }}: {{ $comment }}</p>
                    @endif
                @endforeach
                @foreach ($item->attachments as $itemAttachment)
                    <a href="{{ route('practice-reports.attachments.download', $itemAttachment) }}">{{ $itemAttachment->original_name }}</a>
                @endforeach
            </div>
        </article>
    @endforeach
    @if ($version->status === \App\Enums\PracticeReportStatus::PendingTutorReview)
        @error('reviewComment') <div class="alert alert-danger">{{ $message }}</div> @enderror
        <div class="mb-3">
            <label class="form-label" for="reviewComment">Comentario general (obligatorio al devolver)</label>
            <textarea class="form-control" id="reviewComment" wire:model="reviewComment" maxlength="4000"></textarea>
        </div>
        @foreach ([
            'area' => 'Área',
            'period' => 'Periodo',
            'objectives' => 'Objetivos',
            'activities' => 'Actividades realizadas',
            'outcomes' => 'Resultados y aprendizajes',
            'conclusions' => 'Conclusiones',
            'recommendations' => 'Recomendaciones',
        ] as $section => $label)
            <div class="mb-3">
                <label class="form-label" for="comment-{{ $section }}">Observación: {{ $label }} (opcional)</label>
                <textarea class="form-control" id="comment-{{ $section }}" wire:model="sectionComments.{{ $section }}" maxlength="2000"></textarea>
            </div>
        @endforeach
        <button class="btn btn-success" type="button" wire:click="approve">Aprobar reporte</button>
        <button class="btn btn-warning" type="button" wire:click="returnForChanges">Devolver al alumno</button>
    @endif
</section>
