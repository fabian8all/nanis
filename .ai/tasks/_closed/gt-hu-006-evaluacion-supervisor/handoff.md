# Cierre de tarea: gt-hu-006-evaluacion-supervisor

## Objetivo
Refinar GT-HU-006 con la persona solicitante y registrar criterios suficientes para su implementación futura, sin implementar código.

## Decisiones confirmadas
- La rúbrica inicial conserva todos los criterios, campos y escala del formato institucional `EVAL_SUPERVISOREMPRESA.docx`, sin agregar ni quitar elementos. Coordinación podrá modificarla mediante GT-HU-017.
- La evaluación se habilita al aprobarse GT-HU-005, incluso si aún no se completaron las 240 horas; no requiere contador independiente ni confirmación de término.
- La realiza el supervisor que cargó la aceptación empresarial aprobada.
- Hay una evaluación y un envío por práctica. Se puede guardar y editar un borrador hasta enviarlo.
- Al enviarse queda cerrada e inmutable; Coordinación no puede editarla ni devolverla.
- El alumno propietario, el supervisor evaluador y Coordinación pueden consultar el resultado.
- Al crearla se fija la versión publicada vigente de la rúbrica; las publicaciones posteriores no modifican una evaluación en curso.
- Dependencias: plan aprobado de GT-HU-005 y rúbrica publicada de GT-HU-017.

## Archivos actualizados
- `docs/backlog/candidatos-iniciales.md`: criterios refinados de GT-HU-006.
- `docs/sprints/plan-sprints-candidato.md`: dependencias y ciclo de evaluación del Sprint 4.
- `.ai/state/topics/pendientes.md` y `.ai/state/topics/resumen.md`: pendientes y estado refinado.
- `.ai/HANDOFF.md`: continuidad actualizada.

## Validación
- `python3 tools/continuum doctor` desde WSL: 0 problemas críticos y una advertencia de costo estimado del contexto inicial.
- `git diff --check` pasa en los archivos modificados por esta tarea.

## Pendientes
- Las cuatro historias del Sprint 4 ya están refinadas, implementadas y desplegadas; la implementación de HU-006 está documentada en `.ai/tasks/_closed/gt-hu-006-implementacion-evaluacion-supervisor/`.
- Durante la implementación, verificar que el formulario institucional fuente de rúbrica no incluya elementos que contradigan el criterio confirmado de conservar sus campos y escala. No copiar ejemplos con datos personales a la memoria ni versionarlos.
