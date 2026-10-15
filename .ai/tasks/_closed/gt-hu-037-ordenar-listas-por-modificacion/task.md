# Tarea: gt-hu-037-ordenar-listas-por-modificacion

**Creada:** 2026-10-14 · **Tamaño:** large · **Owner:** backend (reclamado 2026-10-14)

## Objetivo
Ordenar las listas de registros de negocio por la modificación que el usuario
ve como más reciente, para que el trabajo actualizado aparezca primero. Cuando
la lista representa una versión o revisión relacionada, debe usarse la
modificación de esa versión/revisión y resolver empates con un orden estable.

## Incluido en el alcance
- Inventariar las listas de negocio visibles para Alumno, Coordinación,
  Supervisor y Tutor, incluidas vistas con historial de versiones.
- Registrar por lista la entidad representada, la fecha relevante disponible
  y el criterio de desempate antes de editar código.
- Ordenar descendentemente por la modificación más reciente que corresponda
  al contenido presentado; para versiones/revisiones, usar el timestamp de la
  versión/revisión mostrada aunque el padre no haya cambiado.
- Añadir desempate estable por identificador y pruebas de orden/empates.
- Mantener filtros, autorización, estados y asociaciones actuales.

## Explícitamente fuera de alcance
- Añadir búsqueda o paginación (GT-HU-038).
- Cambiar estados, fechas persistidas, esquema o lógica de negocio.
- Ordenar listas estáticas, opciones de formulario o paneles que no representen
  registros de negocio.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/sprints/plan-sprints-candidato.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`
- `project-app/app/Livewire/Companies/Index.php`
- `project-app/app/Livewire/Student/Companies/Index.php`
- `project-app/app/Livewire/Student/PracticePlans/Show.php`
- `project-app/app/Livewire/Student/PracticeReports/Show.php`
- `project-app/app/Livewire/PracticeTracking/Index.php`
- `project-app/app/Livewire/Coordinator/PracticeRequests/Index.php`
- `project-app/app/Livewire/Coordinator/PracticeAcceptances/Index.php`
- `project-app/app/Livewire/Coordinator/PracticePlans/Index.php`
- `project-app/app/Livewire/Coordinator/PracticeReports/Index.php`
- `project-app/app/Livewire/Coordinator/PracticeEvaluations/Index.php`
- `project-app/app/Livewire/Coordinator/PracticeSupervisorEvaluations/Index.php`
- `project-app/app/Livewire/Coordinator/PracticeTerminationLetters/Index.php`
- `project-app/app/Livewire/Coordinator/PracticeSurveys/Manage.php`
- `project-app/app/Livewire/Coordinator/Rubrics/Manage.php`
- `project-app/app/Livewire/Tutor/PracticeEvaluations/Index.php`
- `project-app/app/Livewire/Tutor/PracticeReports/Index.php`
- `project-app/app/Livewire/Supervisor/PracticeLetters/Index.php`
- `project-app/app/Livewire/Supervisor/PracticeSurveys/Index.php`
- `project-app/app/Livewire/Supervisor/PracticeEvaluations/Index.php`
- `project-app/app/Livewire/Supervisor/PracticePlans/Index.php`
- `project-app/resources/views/livewire/student/practice-plans/show.blade.php`
- `project-app/resources/views/livewire/student/practice-reports/show.blade.php`
- `project-app/resources/views/livewire/coordinator/practice-reports/index.blade.php`
- `project-app/tests/Feature/PracticeTrackingTest.php`
- `project-app/tests/Feature/CompanyCatalogTest.php`
- `project-app/tests/Feature/StudentCompanyCatalogTest.php`
- `project-app/tests/Feature/CoordinatorPracticeRequestReviewTest.php`
- `project-app/tests/Feature/CoordinatorPracticeAcceptanceTest.php`
- `project-app/tests/Feature/PracticePlanWorkflowTest.php`
- `project-app/tests/Feature/PracticeReportWorkflowTest.php`
- `project-app/tests/Feature/PracticeEvaluationWorkflowTest.php`
- `project-app/tests/Feature/PracticeSupervisorEvaluationWorkflowTest.php`
- `project-app/tests/Feature/PracticeTerminationLetterIssuanceTest.php`
- `project-app/tests/Feature/PracticeSatisfactionSurveyTest.php`
- `project-app/tests/Feature/CoordinatorRubricManagementTest.php`
- `project-app/tests/Feature/SupervisorPracticeAcceptanceTest.php`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-037 y refinamiento)
- `docs/v1.1/sprints/plan-sprints-candidato.md` (Sprint 5)
- Los 18 componentes del write-set y sus respectivas vistas de lista.
- Las pruebas existentes enumeradas en el write-set; añadir pruebas
  específicas para orden relacionado y desempate.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
