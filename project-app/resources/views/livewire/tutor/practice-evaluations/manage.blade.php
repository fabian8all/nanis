<section>
    <h1 class="h3">Evaluación final del tutor</h1>
    <p>Alumno: {{ $request->student->name }} · Empresa: {{ $request->company->name }}</p>
    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif
    @error('evaluation') <div class="alert alert-warning" role="alert">{{ $message }}</div> @enderror

    @if (! $evaluation)
        <p>Reporte aprobado: versión {{ $request->practiceReport->approvedVersion->version }}.</p>
        <button class="btn btn-primary" type="button" wire:click="startEvaluation">Iniciar evaluación</button>
    @elseif ($evaluation->isSubmitted())
        <div class="alert alert-info">Esta evaluación ya fue enviada y no se puede modificar.</div>
        <a class="btn btn-primary" href="{{ route('tutor.practice-evaluations.show', $evaluation) }}">Consultar resultado</a>
    @else
        <p>Reporte fijado: versión {{ $evaluation->reportVersion->version }} · Rúbrica fijada: versión {{ $evaluation->rubricVersion->version }}.</p>
        <form wire:submit="saveDraft">
            @foreach ($evaluation->criteria as $criterion)
                <div class="card mb-3">
                    <div class="card-body">
                        <h2 class="h5">{{ $criterion->position }}. {{ $criterion->name }} ({{ $criterion->weight }}%)</h2>
                        @if ($criterion->description)<p>{{ $criterion->description }}</p>@endif
                        <label class="form-label" for="score-{{ $criterion->id }}">Desempeño</label>
                        <select class="form-select @error('scores.'.$criterion->id) is-invalid @enderror" id="score-{{ $criterion->id }}" wire:model="scores.{{ $criterion->id }}">
                            <option value="">Seleccionar nivel</option>
                            @foreach ($criterion->scale as $level)
                                <option value="{{ $level['value'] }}">{{ $level['label'] }}</option>
                            @endforeach
                        </select>
                        @error('scores.'.$criterion->id) <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            @endforeach
            <button class="btn btn-outline-secondary" type="submit" wire:loading.attr="disabled">Guardar borrador</button>
            <button class="btn btn-primary" type="button" wire:click="submit" wire:loading.attr="disabled">Enviar evaluación</button>
        </form>
    @endif
</section>
