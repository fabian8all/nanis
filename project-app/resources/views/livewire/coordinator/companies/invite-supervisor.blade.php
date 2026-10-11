<section>
    <h1 class="h3">Invitar supervisor</h1>
    <p>Empresa: <strong>{{ $company->name }}</strong></p>
    <p class="text-secondary">El enlace verifica el correo, permite definir una contraseña y vence en siete días.</p>

    <form wire:submit="sendInvitation" class="card card-body">
        <div class="mb-3">
            <label class="form-label" for="email">Correo del supervisor</label>
            <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" wire:model="email" maxlength="255" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">Enviar invitación</button>
            <a class="btn btn-outline-secondary" href="{{ route('coordinacion.companies.index') }}">Cancelar</a>
        </div>
    </form>
</section>
