# Handoff — GT-HU-033 Acciones desde la etapa del Supervisor

**Fecha:** 2026-10-14 · **Rol:** frontend · **Rama:** `sprint/2-navegacion-acciones-operativas`

## Decisión de producto
La persona solicitante eligió dos botones separados: `Consultar carta de
presentación` y `Cargar carta de aceptación`.

## Resultado
- Las acciones aparecen desde la etapa de aceptación en Seguimiento cuando la
  práctica tiene carta de presentación y la carga está permitida.
- Se reutilizan las rutas de consulta de carta y carga de aceptación existentes.
- Los botones solo se muestran al Supervisor autorizado para la práctica; el
  filtro por empresa y los permisos de las rutas existentes se conservan.
- La carga no aparece mientras Coordinación revisa un envío ni después de la
  aceptación. Una carga devuelta vuelve a ofrecer ambos botones.
- Validaciones, almacenamiento privado y revisión de Coordinación permanecen
  sin cambios.

## Archivos
- `project-app/app/Services/PracticeTrackingSummary.php`
- `project-app/resources/views/livewire/practice-tracking/index.blade.php`
- `project-app/tests/Feature/PracticeTrackingTest.php`
- `project-app/tests/Feature/SupervisorPracticeAcceptanceTest.php`
- `docs/v1.1/backlog/backlog-candidato.md`

## Validación
- `docker compose exec -T app php artisan test --filter='PracticeTrackingTest|SupervisorPracticeAcceptanceTest'` — 12 tests, 110 assertions.
- Suite de regresión combinada de Sprint 2 — 41 tests, 300 assertions.
- `docker compose exec -T app vendor/bin/pint --test app/Services/PracticeTrackingSummary.php tests/Feature/PracticeTrackingTest.php tests/Feature/SupervisorPracticeAcceptanceTest.php` — pasó.
- Pint para los diez archivos PHP modificados de Sprint 2 — pasó.

## Pendientes
Ninguno para esta historia.
