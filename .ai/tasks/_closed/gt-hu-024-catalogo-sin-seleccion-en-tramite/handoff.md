# Handoff — GT-HU-024 Catálogo sin solicitud duplicada

**Fecha:** 2026-10-14 · **Rol:** backend · **Rama:** `sprint/2-navegacion-acciones-operativas`

## Decisión de producto
La persona solicitante confirmó que solo la ausencia de solicitud o el estado
`Devuelta` permite seleccionar empresa. `Pendiente`, `Aprobada` y `Completada`
bloquean la selección. Si el alumno selecciona otra empresa cuando la solicitud
está devuelta, el reenvío actualiza esa misma solicitud.

## Resultado
- El catálogo sigue visible desde la barra del Alumno durante todos los estados.
- Los controles de selección se ocultan y los métodos Livewire los rechazan
  mientras exista una solicitud pendiente, aprobada o completada.
- El envío vuelve a comprobar el estado bajo bloqueo de la solicitud para
  impedir solicitudes duplicadas y selección directa manipulada.
- Con estado devuelto, se permite elegir otra empresa activa y se actualiza el
  mismo registro al reenviar; la corrección sin selección conserva la empresa
  existente.
- Se documentó la decisión de estados en el backlog v1.1.

## Archivos
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/app/Livewire/Student/Companies/Index.php`
- `project-app/resources/views/livewire/student/companies/index.blade.php`
- `project-app/app/Livewire/Student/PracticeRequests/Show.php`
- `project-app/tests/Feature/StudentCompanyCatalogTest.php`
- `project-app/tests/Feature/StudentPracticeRequestTest.php`
- `docs/v1.1/backlog/backlog-candidato.md`

## Validación
- `docker compose exec -T app php artisan test --filter='StudentCompanyCatalogTest|StudentPracticeRequestTest'` — 17 tests, 129 assertions.
- `docker compose exec -T app vendor/bin/pint --test app/Livewire/Student/Companies/Index.php app/Livewire/Student/PracticeRequests/Show.php tests/Feature/StudentCompanyCatalogTest.php tests/Feature/StudentPracticeRequestTest.php` — pasó.

## Pendientes
Ninguno para esta historia.
