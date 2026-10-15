<section>
    <h1 class="h3 mb-3">Rúbricas de evaluación</h1>
    @if (session('status')) <div class="alert alert-success" role="status">{{ session('status') }}</div> @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="mb-3">
        <label class="form-label" for="rubricVersionSearch">Buscar versiones de rúbrica</label>
        <input id="rubricVersionSearch" class="form-control" type="search" wire:model.live.debounce.300ms="searchVersions" placeholder="Tipo de rúbrica, versión o estado">
    </div>

    <div class="row g-3">
        @foreach ($rubrics as $rubric)
            <div class="col-md-6">
                <article class="card h-100">
                    <div class="card-body">
                        <h2 class="h5">Rúbrica de {{ $rubric->type->label() }}</h2>
                        <p>Vigente: {{ $rubric->currentVersion ? 'v'.$rubric->currentVersion->version : 'ninguna' }}</p>
                        <button class="btn btn-outline-primary btn-sm" wire:click="newDraft({{ $rubric->id }})">Crear borrador</button>
                        <ul class="list-group list-group-flush mt-2">
                            @forelse ($rubric->versions as $version)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>v{{ $version->version }} — {{ $version->status === 'published' ? 'publicada' : 'borrador' }}</span>
                                    <button class="btn btn-link btn-sm" wire:click="select({{ $rubric->id }}, {{ $version->id }})">Ver / editar</button>
                                </li>
                            @empty
                                <li class="list-group-item text-secondary">{{ trim($searchVersions) !== '' ? 'No hay versiones que coincidan con la búsqueda.' : 'Esta rúbrica aún no tiene versiones.' }}</li>
                            @endforelse
                        </ul>
                        {{ $rubric->versions->links() }}
                    </div>
                </article>
            </div>
        @endforeach
    </div>

    <form wire:submit="save" class="card mt-4">
        <div class="card-body">
            <h2 class="h5">Rúbrica de {{ $selectedVersion->rubric->type->label() }} — versión {{ $selectedVersion->version }}</h2>
            @if ($selectedVersion->isPublished())
                <p class="alert alert-info">Versión publicada: solo lectura.</p>
                <ol>@foreach ($selectedVersion->criteria as $criterion)<li>{{ $criterion->name }} ({{ $criterion->weight }}%)</li>@endforeach</ol>
            @else
                @forelse ($criteria as $i => $criterion)
                    <fieldset class="border rounded p-3 mb-3" wire:key="criterion-{{ $criterion['id'] ?? 'new-'.$i }}">
                        <legend class="float-none w-auto fs-6">Criterio {{ $i + 1 }}</legend>
                        <label class="form-label">Nombre</label><input class="form-control mb-2" wire:model="criteria.{{ $i }}.name">
                        <label class="form-label">Descripción</label><textarea class="form-control mb-2" wire:model="criteria.{{ $i }}.description"></textarea>
                        <label class="form-label">Ponderación (%)</label><input type="number" step="0.01" class="form-control mb-2" wire:model="criteria.{{ $i }}.weight">
                        <label class="form-label">Niveles de escala (de cero al máximo)</label>
                        @foreach ($criterion['scale'] as $j => $level)
                            <div class="input-group mb-1">
                                <span class="input-group-text">Nivel {{ $j }}</span>
                                <input type="number" step="any" min="0" class="form-control" aria-label="Valor nivel {{ $j }}" wire:model="criteria.{{ $i }}.scale.{{ $j }}.value" placeholder="Valor">
                                <input class="form-control" aria-label="Etiqueta nivel {{ $j }}" wire:model="criteria.{{ $i }}.scale.{{ $j }}.label" placeholder="Etiqueta">
                                <button type="button" class="btn btn-outline-danger" wire:click="removeScaleLevel({{ $i }}, {{ $j }})" aria-label="Quitar nivel {{ $j }}">Quitar</button>
                            </div>
                        @endforeach
                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="addScaleLevel({{ $i }})">Agregar nivel</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeCriterion({{ $i }})">Quitar criterio</button>
                    </fieldset>
                @empty
                    <p class="alert alert-warning">Borrador vacío/incompleto. Agrega los criterios y escala requeridos antes de publicar.</p>
                @endforelse
                <button type="button" class="btn btn-outline-secondary" wire:click="addCriterion">Agregar criterio</button>
                <button class="btn btn-primary">Guardar borrador</button>
                <button type="button" class="btn btn-success" wire:click="publish">Validar y publicar</button>
            @endif
        </div>
    </form>
</section>
