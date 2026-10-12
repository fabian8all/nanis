<section>
    <h1 class="h3 mb-3">Resultado de evaluación final del supervisor</h1>
    <p>Alumno: {{ $evaluation->practiceRequest->student->name }} · Supervisor: {{ $evaluation->supervisor->name }}</p>
    <p>Aceptación evaluada: versión {{ $evaluation->acceptanceSubmission->version }} · Rúbrica aplicada: versión {{ $evaluation->rubricVersion->version }}</p>
    <div class="alert alert-success"><strong>Resultado total: {{ number_format($evaluation->score_total, 2) }} / 100</strong></div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>Criterio</th><th>Desempeño</th><th>Ponderación</th><th>Aportación</th></tr></thead>
            <tbody>
                @foreach ($evaluation->criteria as $criterion)
                    <tr>
                        <td>{{ $criterion->name }}@if ($criterion->description)<div class="small text-muted">{{ $criterion->description }}</div>@endif</td>
                        <td>{{ $criterion->score_label }}</td>
                        <td>{{ number_format($criterion->weight, 2) }}%</td>
                        <td>{{ number_format($criterion->contribution, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
