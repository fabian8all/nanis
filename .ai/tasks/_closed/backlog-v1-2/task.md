# Tarea: backlog-v1-2

**Creada:** 2026-10-16 · **Tamaño:** large · **Owner:** (sin asignar) · **Rol:** Gestión de proyecto (ágil/Scrum) (`comun/gestion-proyecto`)

## Objetivo
Generar los documentos candidatos de backlog y sprints de la versión 1.2 a
partir de las modificaciones registradas por la persona solicitante. Asignar
por orquestación un perfil responsable a cada historia/tarea y entregar los
dos documentos también en PDF.

## Incluido en el alcance
- Analizar todas las modificaciones de `docs/v1.2/planeacion/modificaciones.txt`
  y usar su copia formateada en Markdown como apoyo de lectura.
- Proponer historias candidatas con identificadores nuevos, objetivos y
  criterios de aceptación verificables, trazables a las solicitudes fuente.
- Identificar ambigüedades, dependencias y preguntas abiertas que deban
  resolverse antes de planificar o implementar.
- Agrupar todas las historias exactamente una vez en sprints candidatos y
  asignar a cada historia un perfil responsable del catálogo Continuum.
- Crear una versión PDF de cada documento Markdown para los entregables v1.2.
- Mantener el documento como propuesta candidata, sin asumir aprobación.

## Explícitamente fuera de alcance
- Aprobar el backlog en nombre de la persona solicitante.
- Crear ramas o tareas de implementación por historia.
- Implementar cambios en la aplicación o resolver decisiones de producto que
  no estén especificadas en las modificaciones.
- Fijar fechas, duración, estimaciones, capacidad o compromisos de entrega.
- Aprobar el backlog o el plan de sprints en nombre de la persona solicitante.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `docs/v1.2/planeacion/modificaciones.md`
- `docs/v1.2/backlog/backlog-candidato.md`
- `docs/v1.2/sprints/plan-sprints-candidato.md`
- `docs/v1.2/entregables/backlog-candidato.pdf`
- `docs/v1.2/entregables/plan-sprints-candidato.pdf`
- `.ai/tasks/backlog-v1-2/task.md`
- `.ai/tasks/backlog-v1-2/execution-plan.md`
- `.ai/tasks/backlog-v1-2/notes.md`
- `.ai/tasks/backlog-v1-2/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `docs/v1.2/planeacion/modificaciones.txt`
- `docs/v1.2/planeacion/modificaciones.md`
- `docs/v1.1/backlog/backlog-candidato.md` (referencia de estructura y
  convenciones; no reutilizar identificadores)
- `docs/v1.1/sprints/plan-sprints-candidato.md` (referencia para agrupar por
  resultado/dependencias y asignar un rol a cada historia)
- `python3 tools/continuum roles list` (perfiles disponibles para asignación)

## Criterios de aceptación
- Cada modificación fuente queda representada por una o más historias
  candidatas o identificada explícitamente como pregunta abierta.
- Cada historia candidata tiene un identificador único, objetivo y criterios
  de aceptación comprobables.
- Las historias se pueden rastrear hasta las modificaciones de origen y no
  duplican alcance sin justificación.
- Cada historia aparece en exactamente un sprint candidato y tiene un perfil
  responsable explícito en el plan.
- Las ambigüedades y dependencias se presentan como pendientes de decisión,
  no como acuerdos aprobados.
- El documento se identifica como backlog candidato v1.2 y no establece
  fechas, duración, estimaciones ni capacidad.
- El PDF de backlog y el PDF de sprints se generan desde sus respectivas
  versiones Markdown, se abren correctamente y se guardan en `entregables/`.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
