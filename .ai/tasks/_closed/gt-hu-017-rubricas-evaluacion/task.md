# Tarea: gt-hu-017-rubricas-evaluacion

**Creada:** 2026-10-11 · **Tamaño:** medium · **Owner:** gestion-proyecto · **Rol:** gestion-proyecto

## Objetivo
Refinar y documentar la administración, publicación y versionado de las rúbricas de evaluación del supervisor y tutor, incluyendo reglas de edición y cálculo, antes de su implementación.

## Incluido en el alcance
- Definir alcance de edición, autoría, publicación, versionado y conservación de ambas rúbricas.
- Acordar reglas de vigencia para nuevas evaluaciones y el comportamiento de versiones históricas.
- Definir validaciones de ponderaciones y cálculo del resultado.
- Actualizar backlog, plan candidato y memoria Continuum.

## Explícitamente fuera de alcance
- Implementar pantallas, persistencia o lógica de administración de rúbricas.
- Refinar el flujo de captura de HU-006 o HU-016, excepto sus dependencias con versiones publicadas.
- Cambiar el conjunto inicial de criterios, campos o escalas de los formatos institucionales.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/gt-hu-017-rubricas-evaluacion/task.md`
- `.ai/tasks/gt-hu-017-rubricas-evaluacion/notes.md`
- `.ai/tasks/gt-hu-017-rubricas-evaluacion/execution-plan.md`
- `.ai/tasks/gt-hu-017-rubricas-evaluacion/handoff.md`
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md`
- `../../../../docs/v1.0/sprints/plan-sprints-candidato.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`

## Decisiones confirmadas
- Coordinación es el único rol que puede editar y publicar rúbricas.
- Las rúbricas de supervisor y tutor son independientes, cada una con historial y versión vigente propios.
- La primera versión publicada de cada rúbrica conserva los criterios, campos y escala de su formato institucional vigente, sin agregar ni eliminar elementos iniciales.
- En borradores posteriores Coordinación puede editar criterios, descripciones, orden, escala y ponderaciones; también agregar/eliminar/reordenar criterios. La estructura de campos del formulario no es configurable.
- Toda modificación de una versión publicada se hace en un borrador nuevo. Las versiones publicadas son inmutables y no se pueden eliminar.
- Publicar tiene efecto inmediato. Solo una versión vigente por tipo de rúbrica; afecta evaluaciones creadas posteriormente, nunca las existentes ni sus borradores, que conservan la versión fijada al crearse.
- Las ponderaciones deben sumar exactamente 100%. Se calcula un resultado final de 0 a 100, normalizando cada calificación con el máximo de la escala correspondiente y aplicando su ponderación.

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md`
- `../../../../docs/v1.0/sprints/plan-sprints-candidato.md`
- `.ai/tasks/_closed/gt-hu-006-evaluacion-supervisor/handoff.md`
- `AI_COLLABORATION.md`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
