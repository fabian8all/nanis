<section>
    <h1 class="h3 mb-3">Reportes finales y asignación de tutores</h1>
    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif
    @error('report') <div class="alert alert-warning">{{ $message }}</div> @enderror
    <div class="mb-3">
        <label class="form-label" for="practiceReportSearch">Buscar prácticas</label>
        <input id="practiceReportSearch" class="form-control" type="search" wire:model.live.debounce.300ms="search" placeholder="Alumno, empresa o tutor">
    </div>
    <div class="mb-3">
        <label class="form-label" for="tutorSearch">Buscar tutores para asignar</label>
        <input id="tutorSearch" class="form-control" type="search" wire:model.live.debounce.300ms="tutorSearch" placeholder="Nombre o correo">
    </div>
    @if ($tutors->isEmpty())
        <div class="alert alert-secondary">{{ trim($tutorSearch) !== '' ? 'No hay tutores que coincidan con la búsqueda.' : 'No hay tutores disponibles para asignar.' }}</div>
    @else
        {{ $tutors->links() }}
    @endif
    @forelse ($requests as $request)
        @php($report = $request->practiceReport)
        @php($latest = $report?->versions->sortByDesc('version')->first())
        <article class="card mb-3">
            <div class="card-body">
                <h2 class="h5">{{ $request->student->name }} — {{ $request->company->name }}</h2>
                <p>Tutor asignado: {{ $request->tutor?->name ?? 'Sin asignar' }}</p>
                <form wire:submit="assignTutor({{ $request->id }})" class="row g-2 align-items-end mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="tutor-{{ $request->id }}">Asignar tutor</label>
                        <select class="form-select" id="tutor-{{ $request->id }}" wire:model="tutorAssignments.{{ $request->id }}" required>
                            <option value="">Selecciona un tutor</option>
                            @if ($request->tutor && ! $tutors->contains('id', $request->tutor->id))
                                <option value="{{ $request->tutor->id }}">{{ $request->tutor->name }} (asignado)</option>
                            @endif
                            @foreach ($tutors as $tutor)
                                <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>
                            @endforeach
                        </select>
                        @error('tutorAssignments.'.$request->id) <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-auto"><button class="btn btn-outline-primary" type="submit">Guardar asignación</button></div>
                </form>
                @if ($latest)
                    <p>Reporte — versión {{ $latest->version }}: {{ $latest->status->label() }}</p>
                    @foreach ($report->versions as $version)
                        <details class="mb-2">
                            <summary>Consultar versión {{ $version->version }} — {{ $version->status->label() }}</summary>
                            @include('livewire.student.practice-reports.partials.version', ['version' => $version])
                            @if ($version->review_comment)
                                <p>Comentario general: {{ $version->review_comment }}</p>
                            @endif
                            @foreach ($version->section_comments ?? [] as $section => $comment)
                                @if ($comment)
                                    <p>{{ ucfirst($section) }}: {{ $comment }}</p>
                                @endif
                            @endforeach
                            @foreach ($version->attachments as $attachment)
                                <p><a href="{{ route('practice-reports.attachments.download', $attachment) }}">{{ $attachment->original_name }}</a></p>
                            @endforeach
                        </details>
                    @endforeach
                    @if ($latest->status === \App\Enums\PracticeReportStatus::Approved)
                        <button class="btn btn-warning" type="button" wire:click="reopen({{ $latest->id }})">Autorizar reapertura</button>
                    @elseif ($latest->status === \App\Enums\PracticeReportStatus::Draft)
                        <p class="mb-0">Borrador abierto por Coordinación para corrección.</p>
                    @endif
                @else
                    <p class="mb-0">Reporte disponible para el alumno cuando tenga tutor asignado.</p>
                @endif
            </div>
        </article>
    @empty
        <div class="alert alert-info">{{ trim($search) !== '' ? 'No hay prácticas que coincidan con la búsqueda.' : 'Aún no hay prácticas con aceptación y plan aprobados.' }}</div>
    @endforelse
    {{ $requests->links() }}
</section>
