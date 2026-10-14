# Propuesta de sprints para v1.1

**Estado:** secuencia aprobada por la persona solicitante el 2026-10-14. Las
preguntas abiertas del backlog se conservan como puntos de refinamiento antes
de comprometer cada sprint. Sprint 1 se completó en
`sprint/1-acceso-confiable-destinos-rol` y se integró en `develop` mediante el
merge `b345549`. Se creó una tarea Continuum por HU, con asignación del
orquestador a `frontend` o `backend`; las cinco tareas están cerradas.
GT-HU-022, GT-HU-023, GT-HU-027 y GT-HU-034 están implementadas y validadas.
GT-HU-025 fue verificada y quedó cubierta por pruebas de acceso y aislamiento
sin requerir un cambio en la lógica de aplicación.
Sprint 2 se completó en `sprint/2-navegacion-acciones-operativas`; sus cinco
tareas Continuum están cerradas. Las cinco historias están implementadas,
validadas e integradas en `develop` mediante el merge `d90ae3a`. Para
GT-HU-024 se confirmó la matriz de estados y para GT-HU-033 se eligieron
botones separados. El merge `d90ae3a` y el cierre documental están publicados
en `origin/develop`.
Sprint 3 se inició en `sprint/3-expediente-versiones-revision-reporte` desde
`develop`; sus tres tareas Continuum están creadas y asignadas. Para GT-HU-026
se confirmó sustituir “requisitos de cierre” por “Plan de trabajo” como tercer
punto del expediente y mostrar una insignia de estado por documento.
GT-HU-026, GT-HU-035 y GT-HU-036 están implementadas, validadas y sus tareas
cerradas. Sprint 3 se integró en `develop` mediante el merge `c367237`; su
publicación en `origin/develop` forma parte del cierre.
**Base:** [backlog v1.1](../backlog/backlog-candidato.md).

## Criterios de organización

- Agrupar historias por resultado de usuario y dependencias del flujo.
- Resolver primero los errores de acceso que bloquean acciones autorizadas.
- Mantener cada historia dentro de un único sprint.
- Conservar los límites de autorización y los controles v1.0 que ya existen;
  no duplicar funciones disponibles.
- Usar las preguntas abiertas del backlog como puntos de refinamiento previos
  al compromiso del sprint correspondiente.
- No establecer fechas, duración, estimaciones, capacidad ni velocidad.

## Secuencia aprobada

| Sprint | Objetivo | Historias | Resultado esperado |
|---|---|---|---|
| 1 | Acceso confiable y destinos por rol | GT-HU-022, GT-HU-023, GT-HU-025, GT-HU-027, GT-HU-034 | Alumno y Coordinación llegan a su destino correcto; la consulta permitida de la solicitud y el regreso del Supervisor no producen errores 403. |
| 2 | Navegación y acciones operativas por rol | GT-HU-024, GT-HU-028, GT-HU-031, GT-HU-032, GT-HU-033 | Catálogo, documentos y acciones están accesibles desde la navegación correspondiente y conservan sus permisos. |
| 3 | Expediente, versiones y revisión del reporte | GT-HU-026, GT-HU-035, GT-HU-036 | El expediente sigue el orden del proceso, los estados de versiones se entienden y las observaciones particulares se consultan bajo demanda. |
| 4 | Administración del catálogo de empresas | GT-HU-029, GT-HU-030 | El formulario se abre al crear/editar; Coordinación busca empresas y recorre resultados por páginas. |
| 5 | Experiencia transversal de listas | GT-HU-037, GT-HU-038 | Las listas incluidas se ordenan por modificación reciente y las que aún carecen de búsqueda o paginación reciben controles adecuados, sin duplicar los existentes. |

## Detalle por sprint

### Sprint 1 — Acceso confiable y destinos por rol

**Historias:** GT-HU-022, GT-HU-023, GT-HU-025, GT-HU-027, GT-HU-034.

**Dependencias:** las capacidades de autenticación, solicitud, seguimiento y
consulta de cartas de v1.0 (`GT-HU-001`, `GT-HU-002`, `GT-HU-003`,
`GT-HU-010`, `GT-HU-019`) ya están implementadas.

**Salida:** el alumno, Coordinación y Supervisor pueden continuar el flujo por
rutas válidas; se preserva el aislamiento de solicitudes y expedientes.

**Refinamiento resuelto:** sin solicitud, el alumno va al catálogo; con una
solicitud devuelta, al formulario de corrección; con estado pendiente, aprobado
o completado, a Seguimiento. Los estados cancelado y rechazado no existen en el
enum actual y quedan fuera de esta historia. La pregunta sobre cuándo puede
elegirse otra empresa queda para GT-HU-024.

### Sprint 2 — Navegación y acciones operativas por rol

**Historias:** GT-HU-024, GT-HU-028, GT-HU-031, GT-HU-032, GT-HU-033.

**Dependencias:** GT-HU-024 se apoya en las reglas de estado de GT-HU-023; las
rutas y permisos base pertenecen a `GT-HU-001`, `GT-HU-004`, `GT-HU-005`,
`GT-HU-006`, `GT-HU-010`, `GT-HU-013`, `GT-HU-014`, `GT-HU-015` y `GT-HU-017`.

**Salida:** cada rol encuentra sus documentos y acciones en la navegación
correspondiente. Los atajos del Supervisor no amplían su acceso y los rótulos
de Seguimiento describen el destino real.

**Refinamiento requerido:** confirmar si las acciones de aceptación del
Supervisor en GT-HU-033 serán botones separados o un menú desplegable.

### Sprint 3 — Expediente, versiones y revisión del reporte

**Historias:** GT-HU-026, GT-HU-035, GT-HU-036.

**Dependencias:** expediente y permisos (`GT-HU-012`), estados versionados de
aceptación/plan/reporte (`GT-HU-004`, `GT-HU-005`, `GT-HU-007`) y revisión de
reporte (`GT-HU-007`) ya existen en v1.0.

**Salida:** carta de presentación, carta de aceptación, Plan de trabajo,
reporte final aprobado, evaluaciones y carta de término en ese orden. Cada
punto muestra el estado de entrega/revisión/aprobación que corresponda; los
estados de versiones se traducen solo en presentación y las observaciones del
reporte se consultan por sección sin perder contenido ni el comentario general.

**Refinamiento resuelto:** el tercer punto es Plan de trabajo. Reporte,
evaluaciones y carta de término conservan sus puntos propios; las insignias
reflejan únicamente estados existentes en cada flujo.

### Sprint 4 — Administración del catálogo de empresas

**Historias:** GT-HU-029, GT-HU-030.

**Dependencias:** catálogo de Coordinación (`GT-HU-015`); GT-HU-030 depende del
flujo de creación/edición de GT-HU-029.

**Salida:** el catálogo conserva la lista como vista principal; el formulario
se abre en contexto de creación/edición y la búsqueda/paginación no cambia
estados ni asociaciones.

**Refinamiento requerido:** decidir si la búsqueda incluye empresas ocultas.

### Sprint 5 — Experiencia transversal de listas

**Historias:** GT-HU-037, GT-HU-038.

**Dependencias:** inventario de listas y definición del dato de modificación.
Seguimiento (`GT-HU-010`) ya cuenta con búsqueda, filtros y paginación; el
catálogo se cubre en GT-HU-030.

**Salida:** las listas acordadas usan orden descendente estable por la fecha
pertinente y solo reciben búsqueda/paginación cuando les falte alguno de esos
controles. Se mantienen filtros y límites de autorización.

**Refinamiento requerido:** confirmar si se incluyen historiales de versiones,
administración de rúbricas y listas auxiliares; definir fecha y campos de
búsqueda por lista. Empezar con un inventario para delimitar el alcance antes
de implementar.

## Cobertura de historias

| Sprint | IDs |
|---|---|
| 1 | GT-HU-022, GT-HU-023, GT-HU-025, GT-HU-027, GT-HU-034 |
| 2 | GT-HU-024, GT-HU-028, GT-HU-031, GT-HU-032, GT-HU-033 |
| 3 | GT-HU-026, GT-HU-035, GT-HU-036 |
| 4 | GT-HU-029, GT-HU-030 |
| 5 | GT-HU-037, GT-HU-038 |

Las 17 historias del backlog aparecen exactamente una vez. No se fijan fechas,
duración, estimaciones, capacidad ni velocidad. Al iniciar cada sprint se crea
su rama desde `develop` y una tarea Continuum por historia, asignada por el
orquestador al rol/agente adecuado. Para Sprint 1 ya se crearon la rama y las
tareas de GT-HU-022, GT-HU-023, GT-HU-025, GT-HU-027 y GT-HU-034. Las tareas de
Sprint 2 se crearon al iniciar la rama `sprint/2-navegacion-acciones-operativas`;
las tres tareas de Sprint 3 se crearon al iniciar
`sprint/3-expediente-versiones-revision-reporte`. Las de Sprints 4–5 se crearán
al iniciar cada uno. Las ramas de Sprints 1–3 permanecen disponibles y están
integradas en `develop`.
