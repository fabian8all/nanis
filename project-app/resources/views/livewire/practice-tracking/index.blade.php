<section>
    <h1 class="h3 mb-3">Seguimiento de prácticas</h1>

    @if ($pendingCount > 0)
        <div class="alert alert-warning" role="status">
            Tienes {{ $pendingCount }} {{ $pendingCount === 1 ? 'acción pendiente' : 'acciones pendientes' }}. Abre la práctica desde la tabla para atenderla.
        </div>
    @else
        <div class="alert alert-success" role="status">No tienes acciones pendientes en las prácticas visibles.</div>
    @endif

    @if ($canFilter)
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="practiceSearch" class="form-label">Buscar por alumno o empresa</label>
                <input id="practiceSearch" class="form-control" type="search" wire:model.live.debounce.300ms="search" placeholder="Nombre de alumno o empresa">
            </div>
            <div class="col-md-3">
                <label for="stageFilter" class="form-label">Etapa</label>
                <select id="stageFilter" class="form-select" wire:model.live="stageFilter">
                    <option value="">Todas</option>
                    @foreach (['Solicitud', 'Aceptación', 'Plan', 'Evaluación de Supervisor', 'Reporte', 'Evaluación de Tutor', 'Carta de término', 'Cierre'] as $stage)
                        <option value="{{ $stage }}">{{ $stage }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="stateFilter" class="form-label">Estado</label>
                <select id="stateFilter" class="form-select" wire:model.live="stateFilter">
                    <option value="">Todos</option>
                    @foreach (['Pendiente', 'Devuelta', 'Aprobada', 'Aceptada', 'Bloqueada', 'Bloqueado', 'Borrador', 'Devuelto', 'Aprobado', 'Completada', 'Completo'] as $state)
                        <option value="{{ $state }}">{{ $state }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    @if ($rows->isEmpty())
        <div class="alert alert-info">No hay prácticas que coincidan con la consulta.</div>
    @else
        <div class="accordion" id="practiceTrackingAccordion">
            @foreach ($rows as $row)
                @php($collapseId = 'practiceTrackingDetails-'.$row['request']->id)
                @php($headingId = 'practiceTrackingHeading-'.$row['request']->id)
                <article class="accordion-item mb-3" wire:key="practice-tracking-{{ $row['request']->id }}">
                    <div class="d-flex flex-wrap align-items-stretch">
                        <h2 class="accordion-header flex-grow-1" id="{{ $headingId }}">
                            <button
                                class="accordion-button collapsed flex-wrap gap-2"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $collapseId }}"
                                aria-expanded="false"
                                aria-controls="{{ $collapseId }}"
                            >
                                <strong>{{ $row['request']->student->name }}</strong>
                                <span class="text-secondary">— {{ $row['request']->company->name }}</span>
                                <span class="badge text-bg-primary">
                                    {{ $row['headlineStage']['label'] }} · {{ $row['headlineStage']['status'] }}
                                </span>
                                @if ($row['request']->letter)
                                    <span class="small text-secondary">{{ $row['request']->letter->folio }}</span>
                                @endif
                            </button>
                        </h2>
                        @if ($row['recordRoute'])
                            <div class="d-flex align-items-center p-2">
                                <a class="btn btn-sm btn-outline-primary" href="{{ $row['recordRoute'] }}">Abrir expediente</a>
                            </div>
                        @endif
                    </div>
                    <div
                        id="{{ $collapseId }}"
                        class="accordion-collapse collapse"
                        data-bs-parent="#practiceTrackingAccordion"
                        aria-labelledby="{{ $headingId }}"
                    >
                        <div class="accordion-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Etapa</th>
                                            <th>Estado</th>
                                            <th>Detalle</th>
                                            <th>Responsable</th>
                                            <th><span class="visually-hidden">Acción</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($row['stages'] as $stage)
                                            <tr>
                                                <td>{{ $stage['label'] }}</td>
                                                <td>{{ $stage['status'] }}</td>
                                                <td>{{ $stage['detail'] }}</td>
                                                <td>{{ $stage['responsible'] ? ucfirst($stage['responsible']) : '—' }}</td>
                                                <td>
                                                    @if ($stage['route'])
                                                        <a class="btn btn-sm btn-outline-primary" href="{{ $stage['route'] }}">Abrir práctica</a>
                                                    @else
                                                        <span class="text-secondary">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        {{ $rows->links() }}
    @endif
</section>
