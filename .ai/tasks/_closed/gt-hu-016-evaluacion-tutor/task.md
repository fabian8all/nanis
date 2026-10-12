# Tarea: gt-hu-016-evaluacion-tutor

**Creada:** 2026-10-11 · **Tamaño:** medium · **Owner:** gestion-proyecto · **Rol:** gestion-proyecto

## Objetivo
Refinar y documentar la evaluación final del tutor (HU-016), incluyendo disponibilidad, versión del reporte evaluada, envío, visibilidad y retención, antes de su implementación.

## Incluido en el alcance
- Definir quién evalúa, cuándo se habilita y qué versión del reporte queda vinculada.
- Definir captura, envío único, resultados y acceso/retención.
- Alinear criterios con las rúbricas versionadas de HU-017 y el reporte de HU-007.
- Actualizar backlog, plan candidato y memoria Continuum.

## Explícitamente fuera de alcance
- Implementar la evaluación o modificar el formato institucional.
- Cambiar la administración/versionado de rúbricas definido en HU-017.
- Refinar el ciclo de reporte HU-007 o evaluación de supervisor HU-006.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/gt-hu-016-evaluacion-tutor/task.md`
- `.ai/tasks/gt-hu-016-evaluacion-tutor/notes.md`
- `.ai/tasks/gt-hu-016-evaluacion-tutor/execution-plan.md`
- `.ai/tasks/gt-hu-016-evaluacion-tutor/handoff.md`
- `docs/backlog/candidatos-iniciales.md`
- `docs/sprints/plan-sprints-candidato.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`

## Decisiones confirmadas
- Evalúa el tutor/asesor académico asignado que aprobó el reporte final. HU-016 es independiente de HU-006.
- Se habilita al aprobarse el reporte HU-007, incluso antes de completar 240 horas; no requiere confirmación separada de término.
- Se usa la rúbrica independiente del tutor gestionada por HU-017. Al crear la evaluación, se fija su versión publicada vigente y la versión aprobada del reporte; cambios posteriores no la alteran.
- Una evaluación por práctica. El tutor puede guardar/editar borrador antes del único envío; tras enviarla queda cerrada e inmutable, sin edición posterior.
- Todos los criterios deben responderse; no se permite omitir ni marcar “No aplica”.
- El resultado es un único total ponderado de 0 a 100 con detalle por criterio; incluye los criterios de desempeño y de evaluación del reporte.
- Alumno propietario, tutor evaluador y Coordinación pueden consultar resultado y detalle.
- Se conserva indefinidamente en el expediente, sin borrado automático.

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `docs/backlog/candidatos-iniciales.md`
- `docs/sprints/plan-sprints-candidato.md`
- `.ai/tasks/_closed/gt-hu-006-evaluacion-supervisor/handoff.md`
- `.ai/tasks/_closed/gt-hu-007-reporte-final/handoff.md`
- `.ai/tasks/_closed/gt-hu-017-rubricas-evaluacion/handoff.md`
- `AI_COLLABORATION.md`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
