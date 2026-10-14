# Notas de trabajo: gt-hu-006-evaluacion-superviso

Bitácora libre mientras se trabaja la tarea. No es para lectores extenos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- No quedan decisiones funcionales pendientes para el refinamiento de HU-006; las decisiones confirmadas se transcriben en `task.md`.

## Evidencia recopilada
- Backlog candidato GT-HU-006 y agrupación/dependencias del Sprint 4: `../../../../docs/v1.0/backlog/candidatos-iniciales.md`, `../../../../docs/v1.0/sprints/plan-sprints-candidato.md`.
- Fuente institucional de la rúbrica identificada en el backlog: `../../../../docs/v1.0/formatos/EVAL_SUPERVISOREMPRESA.docx`. La persona solicitante confirmó preservar sus criterios, campos y escala como base inicial; no se copió material fuente a la memoria.
- Confirmaciones de la persona solicitante: conservar escala/campos institucionales; habilitar al aprobar HU-005 incluso antes de completar 240 horas; asignar al supervisor que cargó la aceptación aprobada; una evaluación/envío; borrador editable; envío inmutable; resultado visible al alumno, evaluador y Coordinación; fijar la versión vigente al crear la evaluación.

## Callejones sin salida (para no repetirlos)
- No ejecutar `python3` directamente desde PowerShell en este entono; Continuum funciona mediante WSL: `wsl.exe -d Ubuntu -- bash -lc 'cd /home/fabian/code/nanis && python3 tools/continuum ...'`.
