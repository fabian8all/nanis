# Tarea: planificacion-sprints-v1-1

**Creada:** 2026-10-14 · **Tamaño:** medium · **Owner:** gestion-proyecto · **Rol:** Gestión de proyecto (ágil/Scrum) (`comun/gestion-proyecto`)

## Objetivo
Proponer una secuencia de sprints v1.1 a partir del backlog aprobado, agrupando
historias por resultado de usuario y dependencias. Mantener la planificación
sin fechas, duración, estimaciones ni compromisos de capacidad.

## Incluido en el alcance
- Registrar la aprobación del backlog por la persona solicitante.
- Asignar cada una de las 17 historias `GT-HU-022`–`GT-HU-038` exactamente a
  un sprint candidato.
- Documentar objetivos, historias, dependencias, resultados esperados y
  preguntas que deben resolverse antes de comprometer cada sprint.
- Conservar los límites de autorización, cobertura de pruebas y funciones
  v1.0 existentes al planificar las historias.
- Enlazar la propuesta de sprints desde el índice de documentación.
- Dejar Continuum y `.ai/HANDOFF.md` listos para continuar la revisión.

## Explícitamente fuera de alcance
- Establecer fechas, duración, estimaciones, velocidad o capacidad de sprint.
- Crear ramas de sprint o tareas de implementación por historia antes de que
  la persona solicitante revise la propuesta.
- Cambiar el contenido funcional de historias del backlog aprobado o resolver
  decisiones institucionales que siguen abiertas.
- Implementar cambios de aplicación.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/sprints/plan-sprints-candidato.md`
- `docs/README.md`
- `.ai/tasks/_closed/backlog-v1-1/task.md`
- `.ai/tasks/_closed/backlog-v1-1/handoff.md`
- `.ai/tasks/planificacion-sprints-v1-1/task.md`
- `.ai/tasks/planificacion-sprints-v1-1/execution-plan.md`
- `.ai/tasks/planificacion-sprints-v1-1/notes.md`
- `.ai/tasks/planificacion-sprints-v1-1/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`
- `.ai/state/topics/resumen.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/HANDOFF.md`
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `.ai/tasks/_closed/backlog-v1-1/task.md`
- `.ai/tasks/_closed/backlog-v1-1/handoff.md`
- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/Planeacion/modificaciones.md`
- `docs/v1.0/sprints/plan-sprints-candidato.md`

## Criterios de aceptación
- Las 17 historias aparecen una sola vez, sin reutilizar ni renumerar IDs.
- La secuencia respeta dependencias explícitas o las identifica como
  refinamiento bloqueante.
- Cada sprint tiene objetivo, historias y resultado verificable.
- Se documentan las preguntas abiertas pertinentes sin convertirlas en
  supuestos aprobados.
- No se introducen fechas, duración, estimaciones, capacidad ni velocidad.
- El índice enlaza el plan y el control de referencias/consistencia pasa.
