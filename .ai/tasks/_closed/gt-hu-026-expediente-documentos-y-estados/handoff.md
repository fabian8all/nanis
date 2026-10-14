# Handoff — GT-HU-026 Expediente, documentos y estados

**Fecha:** 2026-10-14 · **Rol:** frontend · **Rama:** `sprint/3-expediente-versiones-revision-reporte`

## Refinamiento
El punto 3 del expediente es **Plan de trabajo**. Las insignias muestran el
estado real de entrega/aprobación aplicable a cada documento. El resumen
agregado de requisitos de cierre conserva su función y no se amplían permisos.

## Resultado
- Reordené la vista para mostrar carta de presentación, aceptación, Plan de
  trabajo, reporte final aprobado, evaluaciones y carta de término.
- Añadí insignias de estado en cada punto disponible, con etiquetas que
  respetan los estados existentes.
- Conservé la vista limitada del Supervisor y evité mostrarle estados internos
  de aceptación o revisión del reporte que no estaban visibles antes.
- Los enlaces de consulta permanecen solo donde existe una ruta apropiada para
  el rol; cuando falta un documento no se muestra un enlace inválido.

## Archivos
- `project-app/app/Livewire/PracticeRecords/Show.php`
- `project-app/resources/views/livewire/practice-records/show.blade.php`
- `project-app/tests/Feature/PracticeRecordTest.php`
- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/sprints/plan-sprints-candidato.md`

## Validación
- `docker compose run --rm --no-deps app php artisan test --filter=PracticeRecordTest` — 5 tests, 83 assertions, sin fallos; PHPUnit reportó warnings.
- `git diff --check` — pasó.

## Pendientes
Ninguno para esta historia.
