# Handoff — GT-HU-028 Navegación de Coordinación

**Fecha:** 2026-10-14 · **Rol:** frontend · **Rama:** `sprint/2-navegacion-acciones-operativas`

## Resultado
- Se añadió un enlace visible a `Catálogo`.
- Se agruparon Solicitudes, Aceptaciones, Cartas de término, Planes, Reportes,
  Evaluaciones de tutor, Evaluaciones de supervisor y Encuestas bajo el menú
  accesible `Documentos`.
- Se mantuvo el acceso superior a Seguimiento y Rúbricas.
- No se cambiaron rutas, permisos ni lógica de los módulos.
- Se añadieron pruebas de rutas/visibilidad por rol y de acceso real a Rúbricas.

## Archivos
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/tests/Feature/CoordinatorNavigationTest.php`
- `project-app/tests/Feature/CoordinatorRubricManagementTest.php`

## Validación
- `docker compose exec -T app php artisan test --filter='CoordinatorNavigationTest|CoordinatorRubricManagementTest'` — 10 tests, 50 assertions.
- `docker compose exec -T app vendor/bin/pint --test tests/Feature/CoordinatorNavigationTest.php tests/Feature/CoordinatorRubricManagementTest.php` — pasó.

## Pendientes
Ninguno para esta historia.
