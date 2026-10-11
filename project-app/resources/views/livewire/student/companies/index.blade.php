<section>
    <h1 class="h3 mb-2">Empresas con convenio vigente</h1>
    <p class="text-secondary mb-4">
        Selecciona la empresa donde deseas realizar tu práctica.
    </p>

    @if (session()->has('status'))
        <div class="alert alert-info" role="status">{{ session('status') }}</div>
    @endif

    @if ($selectedCompany)
        <div class="alert alert-success" role="status">
            <h2 class="h5">Empresa seleccionada</h2>
            <p class="mb-1"><strong>{{ $selectedCompany->name }}</strong></p>
            <p class="mb-0">La selección se conserva en tu sesión para el flujo de solicitud.</p>
        </div>
    @endif

    <div class="row g-3">
        @forelse ($companies as $company)
            <div class="col-md-6">
                <article class="card h-100">
                    <div class="card-body">
                        <h2 class="h5 card-title">{{ $company->name }}</h2>
                        <dl class="mb-3">
                            @if ($company->address)
                                <dt>Dirección</dt>
                                <dd>{{ $company->address }}</dd>
                            @endif
                            @if ($company->phone)
                                <dt>Teléfono</dt>
                                <dd>{{ $company->phone }}</dd>
                            @endif
                            @if ($company->email)
                                <dt>Correo electrónico</dt>
                                <dd><a href="mailto:{{ $company->email }}">{{ $company->email }}</a></dd>
                            @endif
                            @if ($company->contact_person)
                                <dt>Persona de contacto</dt>
                                <dd>{{ $company->contact_person }}</dd>
                            @endif
                        </dl>

                        @if ($selectedCompany?->id === $company->id)
                            <span class="badge text-bg-success">Seleccionada</span>
                        @else
                            <button
                                class="btn btn-outline-primary"
                                type="button"
                                wire:click="select({{ $company->id }})"
                                wire:loading.attr="disabled"
                                wire:target="select"
                            >
                                Seleccionar empresa
                            </button>
                        @endif
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <p class="text-secondary">Por el momento no hay empresas disponibles para nuevas prácticas.</p>
            </div>
        @endforelse
    </div>
</section>
