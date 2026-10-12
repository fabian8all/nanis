# Cierre de tarea: gt-hu-017-rubricas-evaluacion

## Objetivo
Refinar GT-HU-017 con la persona solicitante y documentar reglas suficientes para su implementación, sin cambiar código.

## Decisiones confirmadas
- Solo Coordinación puede editar borradores y publicar.
- Las rúbricas de supervisor y tutor son independientes y tienen su propia secuencia de versiones y versión vigente.
- La primera versión publicada mantiene los criterios, campos y escala del formato institucional correspondiente, sin agregar ni eliminar elementos.
- En nuevos borradores, Coordinación puede editar criterios, descripciones, orden, escalas y ponderaciones, además de agregar/eliminar criterios. La estructura de campos del formulario no es configurable.
- Toda modificación de una rúbrica publicada genera un borrador nuevo. Una versión publicada es inmutable e indeleble.
- Publicar es inmediato y deja una única versión vigente por tipo de rúbrica. Solo afecta nuevas evaluaciones; todas las evaluaciones (incluidos borradores) conservan la versión fijada al crearse.
- Las ponderaciones de una versión deben sumar 100%. El total se calcula en escala de 0 a 100: para cada criterio, se divide el puntaje por el máximo de su escala y se aplica la ponderación; luego se suman los resultados ponderados.

## Archivos actualizados
- `docs/backlog/candidatos-iniciales.md`: criterios de GT-HU-017.
- `docs/sprints/plan-sprints-candidato.md`: reglas de rúbricas y dependencias.
- `.ai/state/topics/pendientes.md` y `.ai/state/topics/resumen.md`: estado de HU-017 y pendientes.
- `.ai/HANDOFF.md`: continuidad actualizada.

## Validación
- `python3 tools/continuum doctor` desde WSL: 0 problemas críticos y una advertencia sobre el costo estimado del contexto inicial.
- `git diff --check` pasó para los documentos modificados (configurado para respetar CRLF preexistente).
- No se implementó código ni se copiaron formatos institucionales o ejemplos con datos personales al repositorio.

## Pendientes del Sprint 4
- GT-HU-007 y GT-HU-016 se refinaron en tareas posteriores y están implementadas en Sprint 4.
- La política de retención indefinida de HU-016 y la captura estructurada de HU-007 se documentaron en sus tareas refinadas.
