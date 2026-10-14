# Tarea: gt-hu-008-carta-termino

**Creada:** 2026-10-12 · **Tamaño:** medium · **Owner:** copilot · **Rol:** Backend (`software/backend`)

## Objetivo
Cerrar la definición implementable de la carta de término empresarial. La empresa la entrega firmada al alumno; el sistema no genera el documento, y su contenido se valida por Coordinación dentro del flujo digital de carga/revisión de GT-HU-009.

## Incluido en el alcance
- Usar como criterios de revisión: nombre del alumno, empresa, periodo, total de 240 horas, actividades, fecha y firma del responsable empresarial.
- Mantener la carta como evidencia documental del cierre; no crear un contador de horas.
- Documentar que el ingreso y revisión de la evidencia digital corresponden a GT-HU-009 para no duplicar flujos.

## Explícitamente fuera de alcance
- Generar una plantilla oficial o una carta digital en nombre de la empresa.
- Crear registros/contadores de horas independientes o asumir el procedimiento de 800 horas de estancias.
- Implementar carga, estados, almacenamiento o permisos, que se construirán en GT-HU-009.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/gt-hu-008-carta-termino/task.md`
- `.ai/tasks/gt-hu-008-carta-termino/execution-plan.md`
- `.ai/tasks/gt-hu-008-carta-termino/notes.md`
- `.ai/tasks/gt-hu-008-carta-termino/handoff.md`
- `.ai/HANDOFF.md`
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/tasks/_closed/gt-sprint-5-refinamiento-cierre-expediente-encuesta/handoff.md`
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md` (GT-HU-008/009)
- `../../../../docs/v1.0/casos-de-uso/flujo-candidato-practica.md` (carta de término)

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
