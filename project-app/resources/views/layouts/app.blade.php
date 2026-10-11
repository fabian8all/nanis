<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Nanis') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
</head>
<body>
    @auth
        <nav class="navbar navbar-expand-lg bg-light border-bottom">
            <div class="container">
                <a class="navbar-brand" href="{{ route('coordinacion.companies.index') }}">Nanis</a>
                <span class="navbar-text me-auto">Coordinación</span>
                <form method="POST" action="{{ route('coordinacion.logout') }}">
                    @csrf
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Cerrar sesión</button>
                </form>
            </div>
        </nav>
    @endauth
    <main class="container py-4">
        {{ $slot }}
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>
</html>
