# Handoff: backlog-v1-1

**Fecha:** 2026-10-14 · **Rol:** gestion-proyecto · **Estado:** backlog
aprobado como base de planificación

## Resultado
- Se creó `docs/v1.1/backlog/backlog-candidato.md` con 17 historias
  (`GT-HU-022`–`GT-HU-038`) organizadas en cinco épicas.
- Cada historia propone prioridad, criterios de aceptación y dependencias.
  Las preguntas que dependen de decisión humana quedaron explícitas; no se
  inventaron políticas ni se asumió que las prioridades estén aprobadas.
- La tabla de trazabilidad cubre todos los apartados de
  `docs/v1.1/Planeacion/modificaciones.md`, incluidos búsqueda, paginación y
  orden por fecha de modificación.
- `docs/README.md` enlaza tanto la fuente como el backlog; el propio backlog
  enlaza de regreso a su fuente.
- El contenido reconoce funciones v1.0 ya disponibles: Seguimiento cuenta con
  filtros, búsqueda y paginación; la ruta de Rúbricas existe; la administración
  de empresas ya permite crear/editar y hoy presenta el formulario en la vista.
  Las historias plantean el ajuste solicitado sin duplicar esos módulos.
- La persona solicitante aprobó el backlog el 2026-10-14; sus preguntas abiertas
  pasan al refinamiento de los sprints, sin asumir respuestas no confirmadas.
- Se completó `.ai/tasks/_closed/planificacion-sprints-v1-1/` con una propuesta
  de secuencia de sprints v1.1 sin fechas, duración ni estimaciones.

## Validación
- Se verificaron los 17 encabezados de historia y los IDs consecutivos
  `GT-HU-022`–`GT-HU-038`; el backlog v1.0 utiliza `GT-HU-001`–`GT-HU-021`.
- Comprobación estructural: cada historia incluye prioridad, criterios de
  aceptación y dependencias.
- Comprobación de enlaces: los enlaces del índice y del backlog resuelven a
  archivos existentes.
- `git diff --check` con reconocimiento de CRLF: sin errores.
- No se ejecutaron pruebas de aplicación porque el cambio es exclusivamente
  documental.

## Pendiente de refinamiento
- Definir el destino de alumnos con solicitudes devueltas, canceladas o
  completadas y qué estados permiten volver a elegir empresa.
- Precisar qué incluye “requisitos de cierre”.
- Confirmar si empresas ocultas aparecen en la búsqueda administrativa.
- Elegir botones separados o menú desplegable para las acciones del Supervisor.
- Definir el registro de modificación y el alcance de “todas las listas”,
  incluyendo historiales y catálogos auxiliares.
- Refinar los estados límite, el alcance de listas y los campos de búsqueda
  antes de comprometer e implementar los sprints.
