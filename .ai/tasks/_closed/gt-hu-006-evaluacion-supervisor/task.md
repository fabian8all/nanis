# Tarea: gt-hu-006-evaluacion-superviso

**Creada:** 2026-10-11 · **Tamaño:** medium · **Owner:** gestion-proyecto · **Rol:** gestion-proyecto

## Objetivo
Refinar y documentar los criterios funcionales de GT-HU-006, evaluación final del supervisor, usando el formato institucional como base y registrando las decisiones confirmadas antes de implementarla.

## Incluido en el alcance
- Definir elegibilidad, asignación del supervisor, captura en borrador, envío, inmutabilidad y consulta del resultado.
- Precisar la dependencia con la versión publicada de la rúbrica de GT-HU-017.
- Actualizar el backlog, el plan candidato del Sprint 4 y la memoria de continuidad.

## Explícitamente fuera de alcance
- Implementar pantallas, persistencia o lógica de negocio de la evaluación.
- Rediseñar criterios, campos o escala del formato institucional.
- Refinar GT-HU-007, GT-HU-016 o GT-HU-017, salvo documentar las dependencias de GT-HU-006.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/gt-hu-006-evaluacion-supervisor/task.md`
- `.ai/tasks/gt-hu-006-evaluacion-supervisor/notes.md`
- `.ai/tasks/gt-hu-006-evaluacion-supervisor/execution-plan.md`
- `.ai/tasks/gt-hu-006-evaluacion-supervisor/handoff.md`
- `docs/backlog/candidatos-iniciales.md`
- `docs/sprints/plan-sprints-candidato.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`

## Decisiones confirmadas
- Se conservarán todos los criterios, campos y la escala del formato institucional vigente `EVAL_SUPERVISOREMPRESA.docx` como rúbrica inicial, sin agregar ni eliminar elementos.
- La evaluación se habilita al aprobarse el plan de trabajo, aun si las 240 horas no se han completado; no habrá contador independiente ni una confirmación de término como requisito de habilitación.
- Evalúa el supervisor que cargó la aceptación empresarial aprobada.
- Habrá una evaluación y un envío por práctica. El supervisor podrá guardar y editar un borrador hasta el envío definitivo.
- Al enviarse, la evaluación queda cerrada e inmutable; Coordinación no la edita ni la devuelve.
- El resultado lo consultan el alumno propietario, el supervisor evaluador y Coordinación.
- Al crear la evaluación se fija la versión publicada vigente de la rúbrica; cambios posteriores no alteran una evaluación en curso.
- La evaluación depende de que GT-HU-017 haya publicado una rúbrica y de que GT-HU-005 tenga aprobado el plan de trabajo.

## Fuentes de verdad a leer antes de empeza
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `docs/backlog/candidatos-iniciales.md`
- `docs/sprints/plan-sprints-candidato.md`
- `AI_COLLABORATION.md`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
