<section>
    <h1 class="h3 mb-3">Cartas de término</h1>

    @if (session()->has('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    @if ($letters->isEmpty())
        <div class="alert alert-info">No hay cartas de término cargadas.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Empresa</th>
                        <th>Enviada</th>
                        <th>Estado</th>
                        <th><span class="visually-hidden">Acciones</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($letters as $letter)
                        <tr>
                            <td>{{ $letter->practiceRequest->student->name }}</td>
                            <td>{{ $letter->practiceRequest->company->name }}</td>
                            <td>{{ $letter->submitted_at->format('Y-m-d H:i') }}</td>
                            <td>{{ str($letter->status->value)->replace('_', ' ')->title() }}</td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('coordinacion.practice-termination-letters.review', $letter) }}">
                                    {{ $letter->status === \App\Enums\PracticeTerminationLetterStatus::PendingReview ? 'Revisar' : 'Consultar' }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $letters->links() }}
    @endif
</section>
