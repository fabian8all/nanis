# Tarea: gt-hu-035-estados-versiones-espanol

**Creada:** 2026-10-14 · **Tamaño:** medium · **Owner:** frontend · **Rol:** Frontend (`frontend`)

## Objetivo
Mostrar los estados de las versiones de aceptación, plan de trabajo y reporte
con etiquetas claras en español en todas las vistas de historial afectadas.

## Incluido en el alcance
- Traducir en presentación todos los estados internos visibles de versiones
  de aceptación, plan y reporte.
- Mantener las etiquetas consistentes en vistas de Alumno, Supervisor,
  Coordinación y Tutor donde se consultan versiones.
- Añadir pruebas de los estados representativos y de que no cambian los valores
  persistidos ni las transiciones.

## Explícitamente fuera de alcance
- Cambiar valores guardados, estados, transiciones o reglas de autorización.
- Traducir estados ajenos al historial de versiones de aceptación, plan y
  reporte.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Enums/PracticeAcceptanceStatus.php`
- `project-app/app/Enums/PracticePlanStatus.php`
- `project-app/app/Enums/PracticeReportStatus.php`
- `project-app/resources/views/livewire/coordinator/practice-acceptances/index.blade.php`
- `project-app/resources/views/livewire/coordinator/practice-acceptances/review.blade.php`
- `project-app/resources/views/livewire/supervisor/practice-letters/index.blade.php`
- `project-app/resources/views/livewire/student/practice-plans/show.blade.php`
- `project-app/resources/views/livewire/coordinator/practice-plans/index.blade.php`
- `project-app/resources/views/livewire/coordinator/practice-plans/review.blade.php`
- `project-app/resources/views/livewire/supervisor/practice-plans/index.blade.php`
- `project-app/resources/views/livewire/supervisor/practice-plans/review.blade.php`
- `project-app/resources/views/livewire/student/practice-reports/show.blade.php`
- `project-app/resources/views/livewire/tutor/practice-reports/index.blade.php`
- `project-app/resources/views/livewire/tutor/practice-reports/review.blade.php`
- `project-app/resources/views/livewire/coordinator/practice-reports/index.blade.php`
- `project-app/tests/Feature/PracticePlanWorkflowTest.php`
- `project-app/tests/Feature/PracticeReportWorkflowTest.php`
- `project-app/tests/Feature/CoordinatorPracticeAcceptanceTest.php`
- `project-app/tests/Feature/SupervisorPracticeAcceptanceTest.php`
- `project-app/tests/Unit/VersionStatusLabelTest.php`
- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/sprints/plan-sprints-candidato.md`
- `.ai/tasks/gt-hu-035-estados-versiones-espanol/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-035)
- Los tres enums del write-set y vistas afectadas
- Los tests de flujos de plan, reporte y aceptación

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
