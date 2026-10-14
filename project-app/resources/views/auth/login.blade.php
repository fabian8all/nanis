<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso de Coordinación · {{ config('app.name', 'Nanis') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 mb-3">Acceso</h1>
                        <p class="text-secondary">Coordinación y supervisores invitados.</p>
                        @error('saml')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                        <form method="POST" action="{{ route('login.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="email">Correo electrónico</label>
                                <input
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    autocomplete="username"
                                    required
                                    autofocus
                                >
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password">Contraseña</label>
                                <input
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="current-password"
                                    required
                                >
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button class="btn btn-primary w-100" type="submit">Iniciar sesión</button>
                        </form>
                        @if (config('services.saml2.simulator_enabled') && app()->environment(['local', 'testing']))
                            <a class="btn btn-outline-primary w-100 mt-3" href="{{ route('auth.saml.simulate') }}">
                                Acceso federado UCOL
                            </a>
                        @elseif (config('services.saml2.enabled'))
                            <a class="btn btn-outline-primary w-100 mt-3" href="{{ route('auth.saml.login') }}">
                                Acceso federado UCOL
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
