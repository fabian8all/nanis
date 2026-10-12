<section>
    <h1 class="h3 mb-3">Cartas de término por emitir</h1>

    @if (session()->has('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    @if ($practices->isEmpty())
        <div class="alert alert-info">No hay expedientes pendientes de emisión de carta.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Empresa</th>
                        <th>Periodo</th>
                        <th><span class="visually-hidden">Acciones</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($practices as $practiceRequest)
                        <tr>
                            <td>{{ $practiceRequest->student->name }}</td>
                            <td>{{ $practiceRequest->company->name }}</td>
                            <td>{{ $practiceRequest->start_date->format('d/m/Y') }} al {{ $practiceRequest->end_date->format('d/m/Y') }}</td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('coordinacion.practice-records.show', $practiceRequest) }}">
                                    Revisar expediente
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $practices->links() }}
    @endif
</section>
