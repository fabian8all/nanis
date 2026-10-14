# Tarea: gt-hu-016-implementacion-evaluacion-tutor

**Creada:** 2026-10-12 · **Tamaño:** large · **Owner:** backend · **Rol:** (sin asignar)

## Objetivo
Implementar la evaluación final del tutor sobre el desempeño del alumno y la versión aprobada del reporte, con captura por rúbrica, un único envío y consulta restringida.

## Incluido en el alcance
- Crear una evaluación por práctica solo después de aprobar el reporte HU-007, capturando el tutor que aprobó esa versión, el ID de la versión del reporte y la versión vigente publicada de la rúbrica Tutor.
- Guardar/editar un borrador por el tutor hasta un único envío; exigir un puntaje válido en cada criterio al enviar.
- Calcular resultado ponderado 0–100 normalizando cada criterio según el máximo de su escala y conservar detalle/puntajes aplicados.
- Permitir consulta del resultado final al alumno propietario, tutor evaluador y Coordinación; impedir cambios tras el envío.
- Agregar bandeja y vistas mínimas para Tutor, alumno y Coordinación, más pruebas de autorización, versiones, puntajes y estados.

## Explícitamente fuera de alcance
- Evaluación del supervisor HU-006, cambios a rúbricas HU-017 y cambios al flujo del reporte HU-007.
- Precargar o publicar una rúbrica de tutor con criterios inventados; Coordinación debe completar y publicar la rúbrica en HU-017.
- Administración general de usuarios y reapertura/edición posterior de evaluaciones enviadas.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/gt-hu-016-implementacion-evaluacion-tutor/task.md`
- `.ai/tasks/gt-hu-016-implementacion-evaluacion-tutor/execution-plan.md`
- `.ai/tasks/gt-hu-016-implementacion-evaluacion-tutor/notes.md`
- `.ai/tasks/gt-hu-016-implementacion-evaluacion-tutor/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md`
- `project-app/app/Enums/PracticeEvaluationStatus.php`
- `project-app/app/Models/PracticeEvaluation.php`
- `project-app/app/Models/PracticeEvaluationCriterion.php`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/app/Models/PracticeReportVersion.php`
- `project-app/database/migrations/2026_10_11_000013_create_practice_evaluations_tables.php`
- `project-app/database/seeders/DemoDataSeeder.php`
- `project-app/app/Livewire/Tutor/PracticeEvaluations/Index.php`
- `project-app/app/Livewire/Tutor/PracticeEvaluations/Manage.php`
- `project-app/app/Livewire/Coordinator/PracticeEvaluations/Index.php`
- `project-app/app/Livewire/PracticeEvaluations/Show.php`
- `project-app/routes/web.php`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/resources/views/livewire/student/practice-reports/show.blade.php`
- `project-app/resources/views/livewire/tutor/practice-evaluations/index.blade.php`
- `project-app/resources/views/livewire/tutor/practice-evaluations/manage.blade.php`
- `project-app/resources/views/livewire/coordinator/practice-evaluations/index.blade.php`
- `project-app/resources/views/livewire/practice-evaluations/show.blade.php`
- `project-app/tests/Feature/PracticeEvaluationWorkflowTest.php`
- `project-app/tests/Feature/DemoDataSeederTest.php`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/tasks/_closed/gt-hu-016-evaluacion-tutor/handoff.md`
- `.ai/tasks/_closed/gt-hu-017-implementacion-rubricas/handoff.md`
- `.ai/tasks/_closed/gt-hu-007-implementacion-reporte/handoff.md`
- `project-app/app/Models/Rubric*.php` y `project-app/app/Services/RubricScoreCalculator.php`
- `project-app/app/Models/PracticeReport*.php`
- `project-app/tests/Feature/CoordinatorRubricManagementTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
