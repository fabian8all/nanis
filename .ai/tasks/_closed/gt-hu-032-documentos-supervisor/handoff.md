# Handoff — GT-HU-032 Documentos en navegación del Supervisor

**Fecha:** 2026-10-14 · **Rol:** frontend · **Rama:** `sprint/2-navegacion-acciones-operativas`

## Resultado
- Se agruparon Cartas pendientes, Planes de trabajo, Evaluaciones y Encuestas
  en el menú `Documentos` del Supervisor.
- Los cuatro enlaces usan rutas existentes bajo `supervisor.*`.
- No se cambiaron consultas, autorización, datos ni flujos de los módulos.
- Se añadió cobertura de rutas y ausencia de los enlaces de Supervisor en la
  navegación de Alumno.

## Archivos
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/tests/Feature/SupervisorNavigationTest.php`

## Validación
- `docker compose exec -T app php artisan test --filter='SupervisorNavigationTest|PracticeTrackingTest|SupervisorPracticeAcceptanceTest'` — 13 tests, 96 assertions.
- `docker compose exec -T app vendor/bin/pint --test tests/Feature/SupervisorNavigationTest.php` — pasó.

## Pendientes
Ninguno para esta historia.
