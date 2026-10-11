# Tarea: gt-hu-005-plan-trabajo

**Creada:** 2026-10-11 · **Tamaño:** medium · **Owner:** backend · **Rol:** Backend (`software/backend`)

## Objetivo
Permitir que el alumno documente el plan de trabajo previsto para su práctica,
una vez aprobada por Coordinación la carta de aceptación. El supervisor que
cargó esa aceptación y después Coordinación revisan el plan.

## Incluido en el alcance
- El alumno registra una o más actividades con descripción, fechas de inicio y
  término, responsable en texto libre y resultado esperado.
- Validar que el término de cada actividad no sea anterior al inicio. No
  imponer plazo de entrega ni exigir que las fechas caigan dentro del periodo
  de la práctica.
- El supervisor que envió la carta de aceptación revisa primero; después
  revisa Coordinación. Ambos pueden aprobar o devolver con comentario
  obligatorio.
- El alumno solo edita y reenvía el plan si cualquiera de los revisores lo
  devuelve. Cada versión se conserva con actividades, estados y comentarios.
  Después de una devolución de Coordinación, la nueva versión vuelve primero
  al supervisor.
- El alumno propietario, el supervisor que aceptó la práctica y Coordinación
  pueden consultar las versiones y sus decisiones; solo el revisor de la etapa
  pendiente puede actuar.

## Explícitamente fuera de alcance
- Evaluaciones terminales del supervisor o tutor (GT-HU-006/016).
- Reporte final de actividades (GT-HU-007).
- Cambios al flujo de aceptación empresarial ya implementado en GT-HU-004.
- Fecha límite de entrega, edición después de aprobar o restricciones de fechas
  según el periodo de práctica.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/routes/web.php`
- `project-app/app/Enums/PracticePlanStatus.php`
- `project-app/app/Models/PracticePlan.php`
- `project-app/app/Models/PracticePlanVersion.php`
- `project-app/app/Models/PracticePlanActivity.php`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/app/Models/PracticeAcceptanceSubmission.php`
- `project-app/app/Models/User.php`
- `project-app/app/Livewire/Student/PracticePlans/**`
- `project-app/app/Livewire/Supervisor/PracticePlans/**`
- `project-app/app/Livewire/Coordinator/PracticePlans/**`
- `project-app/database/migrations/*practice_plans*`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/resources/views/livewire/student/practice-requests/show.blade.php`
- `project-app/resources/views/livewire/student/practice-plans/**`
- `project-app/resources/views/livewire/supervisor/practice-plans/**`
- `project-app/resources/views/livewire/coordinator/practice-plans/**`
- `project-app/tests/Feature/PracticePlanWorkflowTest.php`
- `docs/backlog/candidatos-iniciales.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `docs/sprints/plan-sprints-candidato.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/tasks/gt-hu-005-plan-trabajo/**`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md`
- `.ai/tasks/_closed/gt-hu-004-aceptacion-empresa/handoff.md`
- `docs/backlog/candidatos-iniciales.md` (GT-HU-005)
- `docs/sprints/plan-sprints-candidato.md` (Sprint 3)
- `docs/casos-de-uso/flujo-candidato-practica.md`

## Decisiones pendientes
- No hay decisiones funcionales bloqueantes; los criterios confirmados están
  descritos arriba.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
