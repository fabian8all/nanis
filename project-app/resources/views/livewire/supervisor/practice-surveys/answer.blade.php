<section>
    <h1 class="h3 mb-2">Encuesta de satisfacción</h1>
    <p class="text-secondary mb-4">{{ $surveyRequest->practiceRequest->company->name }} · Escala de 1 a 5</p>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form wire:submit="submit">
        @foreach ($surveyRequest->version->questions as $question)
            <fieldset class="card mb-3" wire:key="answer-question-{{ $question->id }}">
                <div class="card-body">
                    <legend class="float-none fs-6">{{ $loop->iteration }}. {{ $question->question }}</legend>
                    <label class="form-label" for="rating-{{ $question->id }}">Calificación</label>
                    <select id="rating-{{ $question->id }}" class="form-select" wire:model="answers.{{ $question->id }}.rating" required>
                        <option value="">Selecciona una calificación</option>
                        @foreach (range(1, 5) as $rating)
                            <option value="{{ $rating }}">{{ $rating }}</option>
                        @endforeach
                    </select>
                    @error("answers.{$question->id}.rating") <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                    @if ($question->allow_comment)
                        <label class="form-label mt-3" for="comment-{{ $question->id }}">Comentario opcional</label>
                        <textarea id="comment-{{ $question->id }}" class="form-control" maxlength="2000" wire:model="answers.{{ $question->id }}.comment"></textarea>
                        @error("answers.{$question->id}.comment") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    @endif
                </div>
            </fieldset>
        @endforeach

        <p class="text-secondary">Al enviar, la respuesta será final e inmutable. Solo Coordinación podrá consultarla.</p>
        <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">Enviar respuesta final</button>
    </form>
</section>
