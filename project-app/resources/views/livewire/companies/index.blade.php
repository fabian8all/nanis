<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Catálogo de empresas</h1>
            <p class="text-secondary mb-0">Administra las empresas disponibles para nuevas solicitudes.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">{{ $editingId ? 'Editar empresa' : 'Agregar empresa' }}</div>
        <div class="card-body">
            <form wire:submit="save">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nombre de la empresa <span aria-hidden="true">*</span></label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" type="text" wire:model="name" maxlength="255" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="address">Dirección</label>
                        <input class="form-control @error('address') is-invalid @enderror" id="address" type="text" wire:model="address" maxlength="255">
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="phone">Teléfono</label>
                        <input class="form-control @error('phone') is-invalid @enderror" id="phone" type="tel" wire:model="phone" maxlength="50">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" wire:model="email" maxlength="255">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="contactPerson">Persona de contacto</label>
                        <input class="form-control @error('contactPerson') is-invalid @enderror" id="contactPerson" type="text" wire:model="contactPerson" maxlength="255">
                        @error('contactPerson') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">
                        {{ $editingId ? 'Guardar cambios' : 'Agregar empresa' }}
                    </button>
                    @if ($editingId)
                        <button class="btn btn-outline-secondary" type="button" wire:click="cancelEdit">Cancelar</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <caption class="visually-hidden">Empresas registradas, su contacto y estado en el catálogo.</caption>
            <thead>
                <tr>
                    <th scope="col">Empresa</th>
                    <th scope="col">Contacto</th>
                    <th scope="col">Estado</th>
                    <th scope="col"><span class="visually-hidden">Acciones</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $company)
                    <tr wire:key="company-{{ $company->id }}">
                        <td>
                            <strong>{{ $company->name }}</strong><br>
                            <span class="text-secondary">{{ $company->address ?: 'Sin dirección registrada' }}</span>
                        </td>
                        <td>
                            {{ $company->contact_person ?: 'Sin persona de contacto' }}<br>
                            <span class="text-secondary">
                                {{ $company->phone ?: 'Sin teléfono' }}
                                @if ($company->email) · {{ $company->email }} @endif
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $company->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                {{ $company->is_active ? 'Disponible' : 'Oculta' }}
                            </span>
                        </td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-success" href="{{ route('coordinacion.companies.supervisors.invite', $company) }}">
                                Invitar supervisor
                            </a>
                            <button class="btn btn-sm btn-outline-primary" type="button" wire:click="edit({{ $company->id }})">
                                Editar
                            </button>
                            <button
                                class="btn btn-sm btn-outline-secondary"
                                type="button"
                                wire:click="toggleActive({{ $company->id }})"
                                wire:confirm="{{ $company->is_active ? '¿Ocultar esta empresa para nuevas solicitudes?' : '¿Volver a mostrar esta empresa?' }}"
                            >
                                {{ $company->is_active ? 'Dar de baja' : 'Reactivar' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-secondary" colspan="4">Todavía no hay empresas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
