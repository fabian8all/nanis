# Tarea: gt-hu-006-implementacion-evaluacion-supervisor

**Creada:** 2026-10-12 · **Tamaño:** medium · **Owner:** backend · **Rol:** (sin asignar)

## Objetivo
Implementar la evaluación final del supervisor que cargó la aceptación empresarial aprobada, habilitada al aprobar el plan y consultable por alumno, supervisor y Coordinación.

## Incluido en el alcance
- Una evaluación por práctica, habilitada con aceptación empresarial aprobada y plan de trabajo aprobado, sin esperar las 240 horas.
- Fijar en el inicio el supervisor de la aceptación aprobada y la versión publicada vigente de la rúbrica Supervisor; conservar snapshots de criterios, escala y ponderación.
- Borrador editable con puntajes parciales válidos; exigir todos los criterios al único envío, calcular total ponderado normalizado de 0 a 100 y cerrar contra cambios posteriores.
- Bandejas y consulta del detalle para el supervisor evaluador, alumno propietario y Coordinación.
- Migración, rutas/vistas, autorización por rol/propiedad y pruebas de elegibilidad, fijación de versión, cálculo, estados y acceso.

## Explícitamente fuera de alcance
- La evaluación de tutor HU-016, reportes finales HU-007 y gestión/publicación de rúbricas HU-017.
- Inventar o publicar criterios institucionales de supervisor; Coordinación debe completar la rúbrica Supervisor vía HU-017.
- Reapertura, borrado o edición de una evaluación enviada.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/gt-hu-006-implementacion-evaluacion-supervisor/task.md`
- `.ai/tasks/gt-hu-006-implementacion-evaluacion-supervisor/execution-plan.md`
- `.ai/tasks/gt-hu-006-implementacion-evaluacion-supervisor/notes.md`
- `.ai/tasks/gt-hu-006-implementacion-evaluacion-supervisor/handoff.md`
- `.ai/HANDOFF.md`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/app/Models/PracticeSupervisorEvaluation.php`
- `project-app/app/Models/PracticeSupervisorEvaluationCriterion.php`
- `project-app/app/Models/PracticeAcceptanceSubmission.php`
- `project-app/database/migrations/2026_10_11_000014_create_practice_supervisor_evaluations_tables.php`
- `project-app/app/Livewire/Supervisor/PracticeEvaluations/Index.php`
- `project-app/app/Livewire/Supervisor/PracticeEvaluations/Manage.php`
- `project-app/app/Livewire/Coordinator/PracticeSupervisorEvaluations/Index.php`
- `project-app/app/Livewire/PracticeSupervisorEvaluations/Show.php`
- `project-app/routes/web.php`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/resources/views/livewire/student/practice-plans/show.blade.php`
- `project-app/resources/views/livewire/supervisor/practice-evaluations/index.blade.php`
- `project-app/resources/views/livewire/supervisor/practice-evaluations/manage.blade.php`
- `project-app/resources/views/livewire/coordinator/practice-supervisor-evaluations/index.blade.php`
- `project-app/resources/views/livewire/practice-supervisor-evaluations/show.blade.php`
- `project-app/tests/Feature/PracticeSupervisorEvaluationWorkflowTest.php`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/tasks/_closed/gt-hu-006-evaluacion-supervisor/handoff.md`
- `.ai/tasks/_closed/gt-hu-017-implementacion-rubricas/handoff.md`
- `project-app/app/Models/PracticeAcceptanceSubmission.php`
- `project-app/app/Models/PracticePlanVersion.php`
- `project-app/app/Services/RubricScoreCalculator.php`
- `project-app/tests/Feature/PracticePlanWorkflowTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
