<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Nanis') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
</head>
<body>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h3">Gestión de prácticas profesionales</h1>
                        <p class="text-secondary mb-0">
                            Prototipo para prácticas operativas y de mandos medios de Gestión Turística.
                        </p>
                        <a class="btn btn-primary mt-4" href="{{ route('login') }}">Acceso de Coordinación</a>
                        <div class="mt-4" x-data="{ detailsVisible: false }">
                            <button class="btn btn-primary" type="button" x-on:click="detailsVisible = !detailsVisible">
                                <span x-text="detailsVisible ? 'Ocultar stack' : 'Ver stack técnico'"></span>
                            </button>
                            <p class="mt-3 mb-0" x-show="detailsVisible" x-cloak>
                                Laravel, Livewire, Alpine.js, Bootstrap y MySQL.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>
</html>
