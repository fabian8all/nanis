# Tarea: gt-hu-013-encuesta-satisfaccion

**Creada:** 2026-10-12 · **Tamaño:** large · **Owner:** copilot (reclamado 2026-10-12)

## Objetivo
Recopilar de forma privada la satisfacción del supervisor directo de cada
práctica mediante una solicitud autenticada de Coordinación, una respuesta
única e inmutable y un cuestionario institucional versionado.

## Incluido en el alcance
- Configurar preguntas y comentario opcional por pregunta; la escala fija de
  cada respuesta es de 1 a 5.
- Publicar versiones inmutables del cuestionario y fijar la versión vigente al
  solicitar una encuesta.
- Solicitar la encuesta al supervisor de la carta de aceptación vigente de la
  práctica; mostrar solicitudes activas al supervisor autenticado.
- Guardar una única respuesta final; permitir a Coordinación cancelar una
  solicitud pendiente y consultar respuestas/cancelaciones.
- Restringir las respuestas a Coordinación. Supervisor solo completa solicitudes
  pendientes; alumno y Tutor no reciben acceso a la encuesta/respuesta.
- Asociar solicitud y respuestas con práctica, empresa y versión de preguntas;
  no bloquear ni modificar el cierre HU-012.
- Limitar a una solicitud por práctica; la cancelación es terminal para esa
  práctica.

## Explícitamente fuera de alcance
- Correo, notificaciones persistentes, vencimiento automático o recordatorios.
- Acceso a encuesta/resultados para alumno, Tutor u otros supervisores.
- Cambios en las evaluaciones terminales o en el cálculo de cierre del expediente.
- Reabrir, editar o volver a responder una encuesta enviada/cancelada.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/database/migrations/2026_10_12_000016_create_practice_satisfaction_surveys_tables.php`
- `project-app/app/Enums/PracticeSurveyVersionStatus.php`
- `project-app/app/Enums/PracticeSurveyRequestStatus.php`
- `project-app/app/Models/PracticeSurveyVersion.php`
- `project-app/app/Models/PracticeSurveyQuestion.php`
- `project-app/app/Models/PracticeSurveyRequest.php`
- `project-app/app/Models/PracticeSurveyAnswer.php`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/app/Livewire/Coordinator/PracticeSurveys/Manage.php`
- `project-app/app/Livewire/Supervisor/PracticeSurveys/Index.php`
- `project-app/app/Livewire/Supervisor/PracticeSurveys/Answer.php`
- `project-app/routes/web.php`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/resources/views/livewire/coordinator/practice-surveys/manage.blade.php`
- `project-app/resources/views/livewire/supervisor/practice-surveys/index.blade.php`
- `project-app/resources/views/livewire/supervisor/practice-surveys/answer.blade.php`
- `project-app/tests/Feature/PracticeSatisfactionSurveyTest.php`
- `docs/backlog/candidatos-iniciales.md`
- `.ai/tasks/gt-hu-013-encuesta-satisfaccion/task.md`
- `.ai/tasks/gt-hu-013-encuesta-satisfaccion/execution-plan.md`
- `.ai/tasks/gt-hu-013-encuesta-satisfaccion/notes.md`
- `.ai/tasks/gt-hu-013-encuesta-satisfaccion/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/tasks/_closed/gt-sprint-5-refinamiento-cierre-expediente-encuesta/handoff.md`
- `docs/backlog/candidatos-iniciales.md` (GT-HU-013; escala 1–5 confirmada)
- `project-app/app/Models/PracticeRequest.php` y `PracticeAcceptanceSubmission.php`
- `project-app/app/Livewire/Coordinator/Rubrics/Manage.php`
- `project-app/app/Livewire/Supervisor/PracticeEvaluations/Index.php`
- `project-app/app/Http/Middleware/EnsureSupervisor.php`
- `project-app/routes/web.php` y `resources/views/layouts/app.blade.php`
- `project-app/tests/Feature/CoordinatorRubricManagementTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
