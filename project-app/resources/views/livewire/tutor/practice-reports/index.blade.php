<section>
    <h1 class="h3 mb-3">Reportes finales asignados</h1>
    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif
    @forelse ($versions as $version)
        <article class="card mb-3">
            <div class="card-body">
                <h2 class="h5">Versión {{ $version->version }} — {{ $version->status->value }}</h2>
                <p>Alumno: {{ $version->report->practiceRequest->student->name }}</p>
                <p>Empresa: {{ $version->report->practiceRequest->company->name }}</p>
                <a class="btn btn-primary" href="{{ route('tutor.practice-reports.review', $version) }}">
                    {{ $version->status === \App\Enums\PracticeReportStatus::PendingTutorReview ? 'Revisar reporte' : 'Consultar historial' }}
                </a>
            </div>
        </article>
    @empty
        <div class="alert alert-info">No hay reportes finales asignados.</div>
    @endforelse
</section>
