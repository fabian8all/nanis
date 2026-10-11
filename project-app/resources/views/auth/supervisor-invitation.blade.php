<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activar cuenta de supervisor · {{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 mb-3">Activar cuenta de supervisor</h1>
                        <p>
                            Invitación para <strong>{{ $invitation->company->name }}</strong> con el correo
                            <strong>{{ $invitation->email }}</strong>. Al completar este formulario se verificará el correo.
                        </p>
                        @error('email') <div class="alert alert-danger" role="alert">{{ $message }}</div> @enderror
                        <form method="POST" action="{{ route('supervisor.invitations.store', $token) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Nombre</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" maxlength="255" required autofocus>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password">Contraseña (mínimo 12 caracteres)</label>
                                <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="new-password" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                                <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                            </div>
                            <button class="btn btn-primary w-100" type="submit">Verificar correo y activar cuenta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
