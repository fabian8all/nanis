# Tarea: planificacion-sprints-agentes

**Creada:** 2026-10-10 · **Tamaño:** large · **Owner:** gestion-proyecto · **Rol:** Gestión de proyecto (ágil/Scrum) (`comun/gestion-proyecto`)

## Objetivo
Organizar el backlog candidato en sprints y proponer su asignación a los roles/agentes correspondientes de Continuum. El plan debe distinguir las decisiones confirmadas de las estimaciones, la capacidad y la cadencia que aún debe validar la persona solicitante.

## Incluido en el alcance
- Refinar criterios, prioridades y dependencias de las historias del backlog candidato.
- Proponer objetivos, alcance y secuencia de sprints según capacidad y cadencia acordadas.
- Asignar las historias y responsabilidades a los roles/agentes pertinentes disponibles en `.ai/roles/`, indicando dependencias y revisiones cruzadas.
- Documentar el plan candidato en `docs/sprints/` y enlazarlo desde el backlog.
- Consultar a la persona solicitante cuando se necesite confirmar capacidad, duración o asignaciones.
- Mantener el alcance actualizado: prácticas operativas y de mandos medios, 240 horas, una evaluación al término y un reporte final; sin actividades intermedias.

## Explícitamente fuera de alcance
- Implementar historias de producto o iniciar el desarrollo de los sprints.
- Comprometer calendario, capacidad o asignación de personas sin validación.
- Planificar funcionalidades de estancias profesionales, evaluaciones/reportes intermedios o el requisito de 800 horas.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `docs/backlog/candidatos-iniciales.md`
- `docs/sprints/**`
- `.ai/HANDOFF.md`
- `.ai/tasks/planificacion-sprints-agentes/**`
- `.ai/state/topics/pendientes.md`
- `.ai/state/topics/resumen.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `docs/requerimientos/vision-inicial.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `docs/backlog/candidatos-iniciales.md`
- `.ai/roles/comun/gestion-proyecto.md` y roles técnicos/agentes aplicables del catálogo activo.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
