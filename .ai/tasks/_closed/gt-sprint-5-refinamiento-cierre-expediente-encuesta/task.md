# Tarea: gt-sprint-5-refinamiento-cierre-expediente-encuesta

**Creada:** 2026-10-12 · **Tamaño:** large · **Owner:** gestion-proyecto · **Rol:** Gestión de proyecto (ágil/Scrum) (`comun/gestion-proyecto`)

## Objetivo
Refinar con la persona solicitante las historias de Sprint 5 para que el cierre documental y académico de una práctica tenga criterios implementables. Documentar el ciclo de carta de término, expediente/avance y encuesta de satisfacción sin inventar políticas institucionales.

## Incluido en el alcance
- GT-HU-008: emisor, contenido, firma y entrega de la carta que acredita las 240 horas, sin contador de horas independiente.
- GT-HU-009: carga del documento por el alumno, formatos/tamaño, revisión, devolución/corrección y condiciones de cierre del expediente.
- GT-HU-010: etapas visibles, estados/avance, pendientes, filtros y acciones disponibles para Coordinación, manteniendo separadas las dos evaluaciones terminales.
- GT-HU-012: documentos que componen el expediente final, acceso por rol, conservación y reglas de integridad/completitud.
- GT-HU-013: responsable y aprobación del cuestionario, preguntas, canal, solicitud al supervisor directo y resguardo de respuestas. La encuesta es independiente de las evaluaciones terminales; solo Coordinación consulta las respuestas.
- Registrar decisiones, preguntas pendientes, dependencias y criterios de aceptación en el backlog y plan candidatos.

## Explícitamente fuera de alcance
- Implementar pantallas, flujos, modelos, migraciones, almacenamiento o integraciones de Sprint 5.
- Cambiar el umbral confirmado de 240 horas o introducir el flujo de 800 horas de estancias profesionales.
- Incorporar los documentos fuente de `docs/formatos/` o copiar datos personales de formatos/documentos institucionales a memoria versionada.
- Refinar o reabrir las historias ya cerradas de Sprint 1–4, salvo actualizar referencias de estado necesarias.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/gt-sprint-5-refinamiento-cierre-expediente-encuesta/task.md`
- `.ai/tasks/gt-sprint-5-refinamiento-cierre-expediente-encuesta/execution-plan.md`
- `.ai/tasks/gt-sprint-5-refinamiento-cierre-expediente-encuesta/notes.md`
- `.ai/tasks/gt-sprint-5-refinamiento-cierre-expediente-encuesta/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`
- `.ai/state/topics/resumen.md`
- `docs/backlog/candidatos-iniciales.md`
- `docs/sprints/plan-sprints-candidato.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`
- `docs/backlog/candidatos-iniciales.md` (GT-HU-008, GT-HU-009, GT-HU-010, GT-HU-012, GT-HU-013)
- `docs/sprints/plan-sprints-candidato.md` (Sprint 5 candidato)
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `.ai/tasks/_closed/gt-hu-007-reporte-final/handoff.md`
- `.ai/tasks/_closed/gt-hu-006-implementacion-evaluacion-supervisor/handoff.md`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
