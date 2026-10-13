# Tarea: backlog-v1-1

**Creada:** 2026-10-13 · **Tamaño:** large · **Owner:** (sin asignar) · **Rol:** Gestión de proyecto (ágil/Scrum) (`comun/gestion-proyecto`)

## Objetivo
Elaborar un backlog candidato, trazable y verificable para las modificaciones
de v1.1, descomponiendo las solicitudes de `docs/v1.1/Planeacion/modificaciones.md`
en épicas e historias listas para revisión de Coordinación y de la persona
solicitante.

## Incluido en el alcance
- Revisar las modificaciones y las fuentes v1.0 pertinentes para entender el
  flujo actual, actores, permisos y funcionalidades ya existentes.
- Agrupar las solicitudes relacionadas en épicas e historias sin perder la
  trazabilidad con cada punto del documento fuente.
- Definir para cada historia actor, necesidad, resultado esperado, criterios de
  aceptación comprobables, dependencias y prioridad candidata.
- Identificar preguntas abiertas, supuestos y decisiones que requieran
  confirmación; no presentarlos como requisitos aprobados.
- Buscar identificadores ya utilizados y asignar identificadores nuevos sin
  reutilizar ni renumerar ninguno.
- Elaborar `docs/v1.1/backlog/backlog-candidato.md` y enlazarlo desde
  `docs/README.md`.

## Explícitamente fuera de alcance
- Implementar cambios de aplicación, rutas, permisos, formularios, navegación,
  búsqueda, ordenamiento o paginación.
- Cambiar la documentación histórica de v1.0 o reescribir
  `docs/v1.1/Planeacion/modificaciones.md`.
- Comprometer fechas, duración de sprints, capacidad, estimaciones o asignación
  de trabajo. Las prioridades serán candidatas y deberán validarse.
- Dar por aprobados requisitos, políticas institucionales, rúbricas o criterios
  de negocio que sigan pendientes de confirmación.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/backlog-v1-1/task.md`
- `.ai/tasks/backlog-v1-1/execution-plan.md`
- `.ai/tasks/backlog-v1-1/notes.md`
- `.ai/tasks/backlog-v1-1/handoff.md`
- `docs/v1.1/backlog/backlog-candidato.md` (nuevo entregable)
- `docs/README.md` (enlace al entregable)

## Fuentes de verdad a leer antes de empezar
- `.ai/HANDOFF.md`
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `docs/v1.1/Planeacion/modificaciones.md`
- `docs/v1.0/backlog/candidatos-iniciales.md`
- `docs/v1.0/sprints/plan-sprints-candidato.md`
- `docs/v1.0/casos-de-uso/flujo-candidato-practica.md`
- `docs/v1.0/requerimientos/vision-inicial.md`
- Handoffs cerrados de implementación de las áreas afectadas, en especial
  `.ai/tasks/_closed/cierre-expediente-roles-carta/handoff.md`.

## Criterios de aceptación de la tarea
- Todas las solicitudes de `modificaciones.md` aparecen vinculadas a una o más
  historias; los requisitos transversales de búsqueda, paginación y orden por
  fecha también tienen cobertura explícita.
- Cada historia tiene criterios de aceptación observables, dependencias y una
  prioridad propuesta, con preguntas abiertas separadas de los requisitos.
- Los identificadores son nuevos y únicos en el repositorio.
- No se agregan fechas ni duraciones de sprint.
- El backlog y el índice de documentación enlazan correctamente y reciben una
  revisión final de consistencia.
