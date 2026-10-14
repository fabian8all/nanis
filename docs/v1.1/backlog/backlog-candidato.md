# Backlog candidato — versión 1.1

**Estado:** aprobado por la persona solicitante el 2026-10-14 como base de
planificación. Las historias traducen las solicitudes de
[`Planeacion/modificaciones.md`](../Planeacion/modificaciones.md); las
preguntas abiertas se conservan para refinamiento y no establecen políticas
institucionales ni compromisos de entrega.

## Propósito

Reducir fricciones de navegación y consulta por rol, corregir accesos rotos y
facilitar la revisión de prácticas, reportes y empresas, conservando los
permisos y reglas del prototipo v1.0.

## Prioridades aprobadas como base de planificación

- **P0 — Bloqueo:** impide consultar o completar una parte esencial del flujo,
  o produce un error de acceso/ruta.
- **P1 — Flujo principal:** mejora navegación, gestión o revisión necesaria en
  el trabajo cotidiano de un actor.
- **P2 — Usabilidad transversal:** orden, búsqueda y controles que facilitan
  encontrar y procesar información.

La persona solicitante aprobó el backlog y estas prioridades como base para
planificar. No determinan por sí solas el orden de implementación, las
estimaciones, la capacidad ni las fechas.

## Épica GT-EP-06 — Acceso y navegación por rol

### GT-HU-022 — Identificar el acceso federado

**Prioridad:** P2 · **Trazabilidad:** Acceso.

Como alumno, quiero identificar el inicio de sesión institucional para elegir
el mecanismo de acceso correcto.

**Criterios de aceptación**

- El botón que hoy dice **Acceder como alumno de prueba** muestra
  **Acceso federado UCOL**.
- En la pantalla de acceso, el botón aparece debajo de **Iniciar sesión**.
- El botón conserva el destino al flujo SAML institucional; no cambia el
  comportamiento del inicio de sesión local para otros roles.

**Dependencias:** ninguna.

### GT-HU-023 — Dirigir al alumno según el estado de su práctica

**Prioridad:** P1 · **Trazabilidad:** Alumno, acceso inicial.

Como alumno, quiero entrar al área correspondiente a mi situación para
continuar el trámite sin buscar manualmente su siguiente etapa.

**Criterios de aceptación**

- Un alumno sin solicitud de práctica accede al catálogo de empresas con
  convenio.
- Un alumno con una solicitud ya creada o una práctica en proceso es dirigido
  a **Seguimiento**.
- La decisión se basa en el estado vigente de la solicitud/práctica y no solo
  en un valor de sesión antiguo.
- El acceso y la redirección no exponen prácticas de otros alumnos.

**Dependencias:** autenticación institucional y flujo/estados de solicitud v1.0
(`GT-HU-019`, `GT-HU-001`, `GT-HU-010`).

**Pregunta abierta:** definir el destino para solicitudes devueltas, rechazadas
si se llegara a permitir ese estado, canceladas y prácticas ya completadas.

### GT-HU-024 — Consultar empresas sin duplicar una solicitud en trámite

**Prioridad:** P1 · **Trazabilidad:** Alumno, navegación y catálogo.

Como alumno, quiero consultar el catálogo desde la barra de navegación sin
poder iniciar otra selección mientras mi solicitud está en trámite.

**Criterios de aceptación**

- La barra del alumno ofrece un enlace al catálogo de empresas con convenio.
- El catálogo sigue siendo consultable cuando el alumno tiene una solicitud
  en trámite.
- Mientras exista una solicitud en trámite, las acciones para seleccionar
  empresa no están disponibles y no se puede establecer una nueva selección
  desde una acción directa o manipulada.
- Sin solicitud en trámite, la selección conserva el comportamiento v1.0 y
  solo permite empresas activas.

**Dependencias:** `GT-HU-014` y `GT-HU-001`; reglas de estado de
`GT-HU-023`.

**Pregunta abierta:** confirmar qué estados, además de la solicitud devuelta
para corrección, dejan de contar como “en trámite”.

### GT-HU-027 — Abrir Seguimiento al ingresar como Coordinación

**Prioridad:** P1 · **Trazabilidad:** Coordinación, destino posterior al acceso.

Como coordinador, quiero llegar a **Seguimiento** al ingresar para iniciar mi
trabajo desde la vista de prácticas.

**Criterios de aceptación**

- Después de una autenticación válida, Coordinación llega a la pantalla de
  seguimiento de prácticas.
- Una sesión no autenticada que solicite directamente esa pantalla debe
  autenticarse antes de verla.
- Los destinos de Supervisor, Tutor y Alumno no cambian como efecto colateral.

**Dependencias:** `GT-HU-010`.

### GT-HU-028 — Organizar la navegación de Coordinación

**Prioridad:** P1 · **Trazabilidad:** Coordinación, barra superior y rúbricas.

Como coordinador, quiero encontrar el catálogo y los documentos desde una
navegación agrupada, con acceso operativo a Rúbricas.

**Criterios de aceptación**

- La barra superior incluye un enlace al catálogo de empresas.
- Un menú **Documentos** agrupa Solicitudes, Aceptaciones, Cartas de término,
  Planes, Reportes, Evaluaciones de tutor, Evaluaciones de supervisor y
  Encuestas.
- El enlace **Rúbricas** lleva al módulo de administración existente, sin
  mostrar `404 - Not Found`.
- Cada enlace apunta a la ruta del rol Coordinación y conserva la autorización
  de esa ruta.

**Dependencias:** rutas existentes de Coordinación y administración de rúbricas
(`GT-HU-017`).

### GT-HU-032 — Agrupar documentos en la navegación de Supervisor

**Prioridad:** P1 · **Trazabilidad:** Supervisor, barra superior.

Como supervisor, quiero acceder a mis cartas pendientes, planes, evaluaciones y
encuestas desde un solo menú.

**Criterios de aceptación**

- La barra superior ofrece un menú **Documentos** con Cartas pendientes,
  Planes de trabajo, Evaluaciones y Encuestas.
- Cada opción conduce a la lista existente del Supervisor.
- Las rutas y datos siguen limitados al Supervisor y a su empresa asignada.

**Dependencias:** rutas y permisos de Supervisor v1.0 (`GT-HU-004`,
`GT-HU-005`, `GT-HU-006`, `GT-HU-013`).

## Épica GT-EP-07 — Consulta del expediente y acciones de seguimiento

### GT-HU-025 — Consultar la solicitud propia del alumno

**Prioridad:** P0 · **Trazabilidad:** Alumno, error `403 - Forbidden`.

Como alumno, quiero consultar mi propia solicitud para revisar el trámite y
responder a las acciones que me correspondan.

**Criterios de aceptación**

- El alumno autenticado puede consultar la solicitud que le pertenece sin
  recibir `403 - Forbidden`.
- El alumno no puede consultar solicitudes de otras personas cambiando una
  URL o identificador.
- Coordinación conserva el acceso de revisión que ya tiene.
- La corrección no amplía el acceso del Supervisor ni del Tutor a datos de
  solicitud que no les corresponden.

**Dependencias:** autorización y consulta de solicitud (`GT-HU-001`,
`GT-HU-002`).

### GT-HU-026 — Ordenar los documentos del expediente y evitar enlaces vacíos

**Prioridad:** P1 · **Trazabilidad:** Alumno, expediente.

Como usuario autorizado del expediente, quiero encontrar los documentos en el
orden del proceso y distinguir los que aún no están disponibles.

**Criterios de aceptación**

- El expediente presenta, en este orden: carta de presentación; carta de
  aceptación; requisitos de cierre; reporte final aprobado; evaluaciones;
  carta de término.
- Si un documento todavía no existe o no está disponible para ese rol, no se
  muestra un botón **Consultar** activo que apunte a un enlace roto. Se puede
  ocultar o deshabilitar según el diseño final.
- Se conserva la matriz de permisos vigente del expediente; la reorganización
  visual no habilita documentos adicionales a ningún rol.

**Dependencias:** expediente y permisos (`GT-HU-012`).

**Pregunta abierta:** acordar qué comprende exactamente “requisitos de cierre”
y si se presenta como sección o como documentos individuales.

### GT-HU-031 — Nombrar los botones de Seguimiento por acción y destino

**Prioridad:** P1 · **Trazabilidad:** Coordinación, Seguimiento.

Como usuario de Seguimiento, quiero que cada botón indique la acción y el
documento o trámite de destino antes de abrirlo.

**Criterios de aceptación**

- Los botones de cada etapa identifican la acción y el objeto de destino; no
  usan un rótulo genérico cuando la acción concreta puede nombrarse.
- Para abrir una solicitud en revisión se muestra **Revisar solicitud**, en
  lugar de **Abrir práctica**.
- Los rótulos reflejan el destino real de la ruta y no alteran la autorización
  ni la operación del botón.

**Dependencias:** Seguimiento (`GT-HU-010`).

### GT-HU-033 — Acceder a la presentación y aceptación desde la etapa del Supervisor

**Prioridad:** P1 · **Trazabilidad:** Supervisor, etapa de aceptación.

Como supervisor asignado, quiero consultar la carta de presentación o cargar
la carta de aceptación desde la práctica correspondiente.

**Criterios de aceptación**

- En la etapa de aceptación se ofrecen acciones directas para consultar la
  carta de presentación y cargar la carta de aceptación.
- El Supervisor solo ve acciones de prácticas/empresas que tiene autorizadas.
- El flujo conserva validaciones, almacenamiento privado y revisión de
  Coordinación de la carga de aceptación.

**Dependencias:** seguimiento, cartas y carga de aceptación (`GT-HU-004`,
`GT-HU-010`).

**Pregunta abierta:** confirmar si las acciones aparecen como botones separados
o en un menú desplegable.

### GT-HU-034 — Regresar a Seguimiento después de consultar la presentación

**Prioridad:** P0 · **Trazabilidad:** Supervisor, navegación de regreso y error
`403 - Forbidden`.

Como supervisor, quiero volver a Seguimiento después de consultar una carta de
presentación para continuar con una ruta que tengo autorizada.

**Criterios de aceptación**

- La acción de volver desde la carta de presentación conduce a Seguimiento del
  Supervisor y no al expediente restringido.
- El regreso funciona al abrir la carta desde Seguimiento y también al entrar
  directamente a la carta sin una página previa.
- El destino de regreso no permite redirigir a una ruta arbitraria ni eludir
  permisos.

**Dependencias:** consulta autorizada de cartas y seguimiento (`GT-HU-003`,
`GT-HU-010`).

### GT-HU-035 — Mostrar en español el estado de versiones

**Prioridad:** P1 · **Trazabilidad:** Supervisor, títulos automáticos.

Como usuario que revisa documentos, quiero leer los estados en español para
entender la situación de cada versión.

**Criterios de aceptación**

- Los títulos de versiones muestran una etiqueta comprensible en español; por
  ejemplo, no se presenta `Versión 1 - pending_supervisor_review`.
- Los estados internos existentes tienen una traducción visible consistente
  en todas las vistas de historial afectadas.
- La traducción solo cambia la presentación; no modifica valores persistidos,
  transiciones ni reglas de estado.

**Dependencias:** estados de versiones de aceptación, plan y reporte
(`GT-HU-004`, `GT-HU-005`, `GT-HU-007`).

## Épica GT-EP-08 — Administración del catálogo de empresas

### GT-HU-029 — Mostrar el formulario de empresa solo al crear o editar

**Prioridad:** P1 · **Trazabilidad:** Coordinación, catálogo de empresas.

Como coordinador, quiero abrir el formulario solo cuando voy a agregar o
editar una empresa para mantener la lista como vista principal del catálogo.

**Criterios de aceptación**

- En la carga inicial del catálogo no se muestra el formulario de edición.
- **Agregar nueva empresa** abre un formulario nuevo; **Editar** abre el
  formulario con los datos de la empresa elegida.
- Cancelar cierra o abandona la edición sin guardar cambios; guardar crea o
  actualiza únicamente la empresa seleccionada.
- El formulario conserva validaciones y campos existentes.

**Dependencias:** catálogo de Coordinación (`GT-HU-015`).

### GT-HU-030 — Buscar y paginar el catálogo de Coordinación

**Prioridad:** P2 · **Trazabilidad:** Coordinación, catálogo de empresas.

Como coordinador, quiero buscar empresas por nombre y recorrer sus resultados
por páginas para gestionar catálogos extensos.

**Criterios de aceptación**

- La lista permite filtrar empresas por coincidencia en el nombre.
- Se puede avanzar y retroceder entre páginas; el tamaño de página es
  consistente y no deja registros sin alcanzar.
- Al cambiar la búsqueda, se vuelve a la primera página y se muestra un estado
  vacío explícito cuando no hay coincidencias.
- Buscar o paginar no altera el estado activo/inactivo ni las asociaciones
  existentes.

**Dependencias:** catálogo de Coordinación (`GT-HU-015`, `GT-HU-029`).

**Pregunta abierta:** confirmar si la búsqueda debe incluir empresas ocultas
además de las disponibles.

## Épica GT-EP-09 — Revisión del reporte final por Tutor

### GT-HU-036 — Mostrar observaciones por sección bajo demanda

**Prioridad:** P1 · **Trazabilidad:** Asesor/Tutor, revisión del reporte final.

Como tutor, quiero abrir solo las observaciones de las secciones que necesito
comentar para mantener visible el campo de comentario general.

**Criterios de aceptación**

- Las áreas de observación particulares están ocultas inicialmente.
- Cada campo del reporte tiene una acción identificable y accesible para
  mostrar u ocultar su observación correspondiente; al abrirla, el área de
  texto aparece debajo del campo al que comenta.
- Abrir o cerrar una observación no borra su contenido ni cambia los datos del
  reporte.
- **Comentario general** permanece visible debajo del reporte y de las
  observaciones por sección; es el único campo de comentario fijo al pie y
  conserva su carácter obligatorio al devolver el reporte.
- Las observaciones por sección siguen siendo opcionales y se guardan con la
  versión revisada.

**Dependencias:** revisión del reporte final (`GT-HU-007`).

## Épica GT-EP-10 — Búsqueda, paginación y actualización de listas

### GT-HU-037 — Ordenar las listas por modificación más reciente

**Prioridad:** P2 · **Trazabilidad:** Todas las listas.

Como usuario de la plataforma, quiero ver primero los registros modificados
más recientemente para localizar el trabajo actualizado.

**Criterios de aceptación**

- Las listas de registros de negocio para los roles Alumno, Coordinación,
  Supervisor y Tutor se ordenan por fecha de modificación descendente.
- En cada lista, el elemento con la modificación más reciente aparece primero.
- Los empates se resuelven de manera estable para que el orden no cambie
  arbitrariamente entre páginas.
- La fecha usada corresponde a la modificación relevante del registro
  presentado y no a una fecha de creación; si se toma la fecha de una entidad
  relacionada, el criterio queda documentado para esa lista.

**Dependencias:** inventario de listas y definición del dato de modificación
que representa cada una.

**Pregunta abierta:** confirmar cómo ordenar vistas cuyo contenido cambia por
una versión o revisión relacionada sin actualizar el registro padre.

### GT-HU-038 — Buscar y paginar las listas que aún no lo permiten

**Prioridad:** P2 · **Trazabilidad:** Todas las listas.

Como usuario de la plataforma, quiero buscar y paginar listas extensas para
encontrar registros sin recorrer todos los resultados manualmente.

**Criterios de aceptación**

- Se inventarían las listas de registros de negocio visibles por rol y se
  identifican las que ya tienen búsqueda y paginación.
- Toda lista de negocio que carezca de cualquiera de esos controles incorpora
  búsqueda pertinente a sus campos visibles y paginación.
- La búsqueda y paginación respetan el rol, el alcance de los registros y los
  filtros ya disponibles; un cambio de búsqueda o filtro vuelve a la primera
  página.
- Cada lista muestra un estado vacío informativo cuando no hay coincidencias.
- Las listas que ya tienen ambos controles no reciben controles duplicados.

**Dependencias:** inventario y clasificación de listas; catálogo de Coordinación
se cubre específicamente en `GT-HU-030`; Seguimiento ya tiene búsqueda,
filtros y paginación (`GT-HU-010`).

**Pregunta abierta:** confirmar si la solicitud incluye historiales de
versiones, listas administrativas de rúbricas y opciones de selección, o solo
las bandejas/listas principales de registros.

## Trazabilidad de las solicitudes

| Solicitud en `modificaciones.md` | Historias |
|---|---|
| Acceso: texto y posición del botón federado | GT-HU-022 |
| Alumno: destino inicial, catálogo, selección y consulta de solicitud | GT-HU-023, GT-HU-024, GT-HU-025 |
| Alumno: orden del expediente y documentos no disponibles | GT-HU-026 |
| Coordinación: ingreso, navegación, documentos y enlace de Rúbricas | GT-HU-027, GT-HU-028 |
| Coordinación: formulario de empresa, búsqueda y paginación | GT-HU-029, GT-HU-030 |
| Coordinación: rótulos de acciones en Seguimiento | GT-HU-031 |
| Supervisor: menú de documentos | GT-HU-032 |
| Supervisor: acciones de aceptación y regreso a Seguimiento | GT-HU-033, GT-HU-034 |
| Supervisor: títulos de versiones en español | GT-HU-035 |
| Asesor/Tutor: observaciones por sección | GT-HU-036 |
| Todas las listas: orden por modificación | GT-HU-037 |
| Todas las listas: búsqueda y paginación donde falten | GT-HU-030, GT-HU-038 |

## Preguntas para revisión con Coordinación y la persona solicitante

1. ¿Qué estados de solicitud/práctica deben llevar al Alumno a Seguimiento y
   cuáles permiten volver a seleccionar empresa?
2. ¿Qué secciones y documentos exactos forman los “requisitos de cierre” del
   expediente?
3. ¿La búsqueda del catálogo debe incluir empresas ocultas?
4. ¿El acceso directo del Supervisor a aceptación se presenta como dos botones
   o como un menú?
5. ¿Qué fecha representa la última modificación en bandejas con versiones,
   evaluaciones o revisiones relacionadas?
6. ¿“Todas las listas” incluye historiales de versiones, administración de
   rúbricas y listas de opciones, o se limita a listas principales de registros?
7. ¿Qué registros y campos de búsqueda corresponden a cada bandeja por rol?

## Dependencias y notas de refinamiento

- Los IDs `GT-HU-001` a `GT-HU-021` ya están utilizados en el backlog v1.0,
  incluido `GT-HU-011`, que está explícitamente excluido; las historias de este
  documento continúan en `GT-HU-022` y no reutilizan identificadores.
- Las historias que corrigen `403` o `404` deben conservar la autorización
  vigente y añadir pruebas de acceso permitido/denegado para los roles
  afectados.
- La búsqueda, el orden y la paginación deben mantener los filtros, los
  límites de autorización por rol y el comportamiento de pantallas con cero
  resultados.
-   La distribución de historias en sprints se documenta por separado en
  [`../sprints/plan-sprints-candidato.md`](../sprints/plan-sprints-candidato.md)
  y requiere revisión. No se establecen fechas, duración ni estimaciones.
