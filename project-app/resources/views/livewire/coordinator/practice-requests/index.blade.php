<section>
    <h1 class="h3 mb-2">Solicitudes de práctica</h1>
    <p class="text-secondary mb-4">Solicitudes pendientes de revisión por Coordinación.</p>

    @if (session()->has('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    <div class="mb-3">
        <label class="form-label" for="practiceRequestSearch">Buscar solicitudes</label>
        <input id="practiceRequestSearch" class="form-control" type="search" wire:model.live.debounce.300ms="search" placeholder="Alumno, correo, empresa o grado/grupo">
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <caption class="visually-hidden">Solicitudes de práctica pendientes de revisión.</caption>
            <thead>
                <tr>
                    <th scope="col">Alumno</th>
                    <th scope="col">Empresa</th>
                    <th scope="col">Grado/grupo</th>
                    <th scope="col">Periodo solicitado</th>
                    <th scope="col"><span class="visually-hidden">Acciones</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $request)
                    <tr wire:key="practice-request-{{ $request->id }}">
                        <td>
                            <strong>{{ $request->student->name }}</strong><br>
                            <span class="text-secondary">{{ $request->student->email }}</span>
                        </td>
                        <td>{{ $request->company->name }}</td>
                        <td>{{ $request->grade_group }}</td>
                        <td>{{ $request->start_date->format('d/m/Y') }} – {{ $request->end_date->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-primary" href="{{ route('coordinacion.practice-requests.review', $request) }}">Revisar</a>
                        </td>
                    </tr>
                @empty
                    <tr><td class="text-secondary" colspan="5">{{ trim($search) !== '' ? 'No hay solicitudes que coincidan con la búsqueda.' : 'No hay solicitudes pendientes de revisión.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $requests->links() }}
</section>
