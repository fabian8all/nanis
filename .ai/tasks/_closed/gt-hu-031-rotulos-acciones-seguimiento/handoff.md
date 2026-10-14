# Handoff — GT-HU-031 Rótulos de acciones de Seguimiento

**Fecha:** 2026-10-14 · **Rol:** frontend · **Rama:** `sprint/2-navegacion-acciones-operativas`

## Resultado
- Los botones de acción de Seguimiento ahora describen la operación y el
  destino, en vez de repetir `Abrir práctica`.
- La revisión de una solicitud se etiqueta `Revisar solicitud`; las solicitudes
  devueltas, `Corregir solicitud`.
- Se añadieron rótulos específicos para aceptar/revisar, plan, reporte,
  evaluaciones y expediente.
- El destino sigue viniendo de la ruta previamente autorizada; un destino sin
  rótulo explícito produce un error, no una etiqueta genérica.

## Archivos
- `project-app/app/Services/PracticeTrackingSummary.php`
- `project-app/resources/views/livewire/practice-tracking/index.blade.php`
- `project-app/tests/Feature/PracticeTrackingTest.php`

## Validación
- `docker compose exec -T app php artisan test --filter=PracticeTrackingTest` — 6 tests, 44 assertions.
- `docker compose exec -T app vendor/bin/pint --test app/Services/PracticeTrackingSummary.php tests/Feature/PracticeTrackingTest.php` — pasó.

## Pendientes
Ninguno para esta historia.
