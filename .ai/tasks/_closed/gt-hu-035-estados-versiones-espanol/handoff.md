# Handoff — GT-HU-035 Estados de versiones en español

**Fecha:** 2026-10-14 · **Rol:** frontend · **Rama:** `sprint/3-expediente-versiones-revision-reporte`

## Resultado
- Añadí etiquetas españolas centralizadas en los enums de aceptación, plan y
  reporte.
- Reemplacé los valores internos en todas las vistas de historial afectadas
  para Alumno, Supervisor, Coordinación y Tutor.
- Los valores persistidos y las transiciones no cambian.

## Archivos
- Los enums `PracticeAcceptanceStatus`, `PracticePlanStatus` y
  `PracticeReportStatus`.
- Las vistas de aceptación, plan y reporte incluidas en el write-set de la
  tarea.
- `project-app/tests/Feature/PracticePlanWorkflowTest.php`
- `project-app/tests/Feature/PracticeReportWorkflowTest.php`
- `project-app/tests/Feature/CoordinatorPracticeAcceptanceTest.php`
- `project-app/tests/Unit/VersionStatusLabelTest.php`
- `docs/v1.1/sprints/plan-sprints-candidato.md`

## Validación
- Regresión dirigida de Sprint 3 — 24 tests, 303 assertions, todos pasaron.
- Pint `--test` para 10 archivos PHP de Sprint 3 — pasó.
- Búsqueda en las vistas confirmó que no quedan estados de versión mostrados
  mediante `status->value`.

## Pendientes
Ninguno para esta historia.
