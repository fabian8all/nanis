# Backlog candidato — versión 1.2

**Estado:** propuesta para refinamiento; no aprobada. Se construyó a partir de
`docs/v1.2/planeacion/modificaciones.txt` y su copia formateada
`docs/v1.2/planeacion/modificaciones.md`. Las historias no sustituyen la
revisión de Coordinación ni de la persona solicitante.

## Propósito

Reducir fricciones en el acceso y la navegación por rol, ayudar al Supervisor
a completar las revisiones asignadas, hacer más claro el expediente y completar
acciones administrativas que actualmente están rotas o no disponibles.

## Convenciones de planificación

- Los identificadores continúan la secuencia de Gestión Turística: `GT-HU-039`
  a `GT-HU-053`. No reutilizan IDs de v1.0 ni v1.1.
- Las prioridades y la capacidad quedan pendientes de refinamiento; no se
  infieren del orden del documento.
- Los criterios describen el resultado esperado sin fijar reglas
  institucionales todavía no acordadas.
- `GT-HU-053` registra un requerimiento adicional señalado en el handoff
  vigente sobre el simulador SAML; no aparece en el archivo
  `modificaciones.txt` y se identifica separadamente en la trazabilidad.

## Épica GT-EP-11 — Perfil y acceso por rol

### GT-HU-039 — Editar los datos personales del perfil

**Prioridad:** pendiente · **Trazabilidad:** M-01.

Como usuario autenticado, quiero editar mis datos personales desde mi perfil
para mantener actualizada mi información sin cambiar el correo de la cuenta.

**Criterios de aceptación**

- El perfil permite consultar y editar los datos personales autorizados.
- El correo electrónico se muestra como no editable desde esta sección.
- Los cambios válidos se guardan en la cuenta del usuario autenticado y se
  reflejan al volver a consultar el perfil.
- Un usuario no puede modificar el perfil de otra cuenta usando una URL o
  petición manipulada.

**Dependencias y refinamiento:** falta acordar la lista exacta de campos
editables y sus reglas de validación.

### GT-HU-040 — Crear o cambiar la contraseña desde el perfil

**Prioridad:** pendiente · **Trazabilidad:** M-01.

Como usuario, quiero establecer o cambiar mi contraseña desde el perfil para
gestionar mi acceso local de forma segura.

**Criterios de aceptación**

- El usuario puede iniciar el flujo para establecer una contraseña cuando
  todavía no tenga una, o cambiarla cuando ya tenga una.
- La contraseña no se muestra ni se devuelve en las respuestas de la
  aplicación y se conserva el mecanismo seguro de almacenamiento existente.
- Tras guardar una contraseña válida, el acceso local acepta la nueva
  credencial; las reglas del acceso federado no se debilitan.
- Las reglas de complejidad, confirmación y verificación de la contraseña
  anterior se documentan antes de comprometer la historia.

**Dependencias y refinamiento:** definir la política de contraseña, la
verificación requerida al cambiarla y el comportamiento para cuentas de acceso
federado sin contraseña local.

### GT-HU-041 — Abrir Seguimiento al ingresar como Coordinación

**Prioridad:** pendiente · **Trazabilidad:** M-02.

Como coordinador, quiero llegar directamente a Seguimiento después de iniciar
sesión para empezar desde mi espacio de trabajo.

**Criterios de aceptación**

- Después de autenticarse, Coordinación llega directamente a Seguimiento.
- No se muestra la página intermedia con enlaces al catálogo y a la revisión
  de solicitudes.
- El destino conserva la autenticación requerida y la autorización de
  Coordinación.
- Los destinos posteriores al acceso de otros roles no cambian como efecto
  colateral.

**Dependencias:** Seguimiento y las rutas protegidas de Coordinación existentes.

### GT-HU-042 — Abrir Seguimiento al ingresar como Supervisor

**Prioridad:** pendiente · **Trazabilidad:** M-03.

Como Supervisor, quiero llegar directamente a Seguimiento al iniciar sesión
para ver el estado de las prácticas que tengo asignadas.

**Criterios de aceptación**

- Después de autenticarse, el Supervisor llega a Seguimiento y no a Cartas
  pendientes de aceptación.
- El contenido de Seguimiento mantiene el alcance de las prácticas y empresas
  asignadas al Supervisor.
- Una solicitud directa de la ruta sigue protegida por autenticación y
  autorización.
- El cambio no altera los destinos de Coordinación, Alumno ni Tutor.

**Dependencias:** Seguimiento y la asignación de prácticas al Supervisor.

### GT-HU-052 — Corregir el acceso de Coordinación a Rúbricas

**Prioridad:** pendiente · **Trazabilidad:** M-13.

Como coordinador, quiero abrir el módulo de Rúbricas desde la navegación para
administrar las rúbricas sin encontrar un error 404.

**Criterios de aceptación**

- El enlace Rúbricas de la barra de Coordinación abre una ruta válida del
  módulo de administración existente.
- Coordinación autorizada puede consultar el módulo; otros roles no obtienen
  permisos adicionales por el cambio de enlace.
- Si el acceso no está autorizado, la aplicación responde según el control
  de acceso existente, no con una ruta inválida.

**Dependencias:** ruta y permisos actuales de administración de rúbricas.

### GT-HU-053 — Configurar la identidad del simulador SAML por entorno

**Prioridad:** pendiente · **Trazabilidad:** solicitud complementaria anotada
en el handoff vigente; no está en `modificaciones.txt`.

Como responsable del entorno de desarrollo, quiero configurar la identidad
demostrativa del simulador SAML mediante el entorno para cambiar la cuenta de
prueba sin modificar código.

**Criterios de aceptación**

- El simulador usa la identidad configurada mediante `SAML_SIMULATOR_EMAIL`
  en el `.env` montado por el entorno de desarrollo.
- Al modificar esa variable, el simulador toma el valor actualizado sin
  requerir reconstruir el contenedor.
- La configuración del simulador no cambia el flujo del inicio de sesión con
  contraseña ni agrega credenciales al repositorio.

**Estado conocido:** el handoff vigente indica que esta capacidad ya se
implementó; antes de comprometer trabajo, verificar el comportamiento actual
y decidir si esta historia debe registrarse como entregada o si falta algo.

## Épica GT-EP-12 — Seguimiento y acciones del Supervisor

### GT-HU-043 — Filtrar las prácticas de Seguimiento por estado

**Prioridad:** pendiente · **Trazabilidad:** M-04.

Como Supervisor, quiero organizar mis prácticas por estado para encontrar con
rapidez la siguiente revisión que debo completar.

**Criterios de aceptación**

- Seguimiento permite identificar y seleccionar prácticas por su estado.
- Cada práctica aparece en la categoría que corresponde a su estado vigente.
- El filtro o agrupación no muestra prácticas fuera del alcance del Supervisor
  autenticado.
- Cambiar el estado de una práctica actualiza su categoría según el estado
  vigente.

**Dependencias y refinamiento:** falta acordar la lista completa de estados y
si la interacción será un filtro o pestañas; la modificación fuente da ambos
como alternativas.

### GT-HU-044 — Abrir directamente el plan de trabajo del alumno

**Prioridad:** pendiente · **Trazabilidad:** M-05.

Como Supervisor, quiero que «Revisar plan de trabajo» abra el plan del alumno
correspondiente para no tener que buscarlo de nuevo en el catálogo.

**Criterios de aceptación**

- La acción abre el plan de trabajo relacionado con la práctica desde la que
  se inició la navegación.
- No dirige al catálogo general de planes.
- La ruta y los datos se limitan al Supervisor y a la práctica autorizados.
- La acción solo se ofrece cuando el flujo permite revisar ese plan.

**Dependencias:** Seguimiento, planes de trabajo y sus controles de autorización.

### GT-HU-045 — Abrir directamente la evaluación terminal

**Prioridad:** pendiente · **Trazabilidad:** M-06.

Como Supervisor, quiero que «Completar evaluación terminal» abra la evaluación
del alumno correspondiente para completar la revisión correcta.

**Criterios de aceptación**

- La acción abre la evaluación terminal asociada a la práctica seleccionada.
- No dirige al catálogo general de evaluaciones.
- La ruta comprueba el rol, la asignación y el estado que habilita la
  evaluación.
- Las evaluaciones de otras prácticas o empresas no quedan visibles mediante
  una URL manipulada.

**Dependencias:** evaluación terminal, asignación del Supervisor y estados del
flujo.

## Épica GT-EP-13 — Expediente y regreso al contexto

### GT-HU-046 — Mostrar los documentos iniciales en los requisitos de término

**Prioridad:** pendiente · **Trazabilidad:** M-07.

Como usuario del expediente, quiero ver el estado de los documentos iniciales
en los requisitos de término para comprender el avance del proceso.

**Criterios de aceptación**

- La sección incluye carta de presentación, carta de aceptación y plan de
  trabajo, cada uno con su estado vigente.
- «Carta de término emitida» aparece después de esos documentos y al final de
  la secuencia de requisitos.
- Los estados reflejan los registros actuales y no modifican por sí mismos
  el flujo de aprobación.
- La vista respeta el alcance de expediente permitido para el rol.

**Dependencias y refinamiento:** confirmar qué otros elementos forman los
«requisitos de término» y las etiquetas de estado disponibles para cada
documento.

### GT-HU-047 — Agrupar los documentos del expediente en un acordeón

**Prioridad:** pendiente · **Trazabilidad:** M-08.

Como usuario del expediente, quiero encontrar los documentos en una sección
plegable para consultar el expediente con menos saturación visual.

**Criterios de aceptación**

- Los documentos del expediente se agrupan bajo una sección identificada como
  «Documentos».
- La sección aparece colapsada al entrar al expediente.
- El control permite expandirla y volver a colapsarla con teclado y tecnologías
  de asistencia.
- La agrupación no oculta documentos a los roles que ya están autorizados para
  consultarlos.

**Dependencias:** vista del expediente y permisos vigentes por rol.

### GT-HU-048 — Mostrar acciones del expediente cuando estén disponibles

**Prioridad:** pendiente · **Trazabilidad:** M-09.

Como usuario del expediente, quiero ver acciones de plan y reporte únicamente
cuando corresponden a la etapa vigente para no llegar a rutas inexistentes.

**Criterios de aceptación**

- «Registrar plan de trabajo» y «Consultar reporte final» no conducen a una
  ruta 404.
- Cada acción aparece o queda deshabilitada hasta que su destino y etapa sean
  válidos.
- La condición que habilita cada acción también se verifica en el servidor;
  ocultar el enlace no sustituye autorización.
- La disponibilidad y el estado mostrado corresponden a la práctica
  seleccionada.

**Dependencias y refinamiento:** definir qué estados habilitan cada acción y
confirmar si la interfaz debe ocultarla o mostrarla deshabilitada antes de
estar disponible.

### GT-HU-049 — Volver al expediente desde la carta de presentación

**Prioridad:** pendiente · **Trazabilidad:** M-10.

Como usuario que abre la carta desde el expediente, quiero regresar al
expediente para continuar revisándolo.

**Criterios de aceptación**

- Al abrir la carta desde el expediente, «Volver» regresa a ese expediente y
  no a Solicitudes de práctica.
- El regreso conserva el contexto del expediente autorizado.
- Un destino de regreso proporcionado por el cliente no permite redirigir a
  sitios externos o a recursos no autorizados.

**Dependencias y refinamiento:** establecer un destino alternativo seguro para
accesos directos a la carta sin contexto de expediente.

### GT-HU-050 — Regresar desde la consulta del plan de trabajo

**Prioridad:** pendiente · **Trazabilidad:** M-11.

Como usuario que consulta un plan desde el expediente, quiero volver a la vista
anterior para continuar con el mismo trámite.

**Criterios de aceptación**

- La vista del plan ofrece una acción de regreso claramente identificada.
- Cuando el plan se abrió desde el expediente, la acción regresa a ese
  expediente.
- El regreso no modifica el plan ni permite acceder a expedientes no
  autorizados.

**Dependencias y refinamiento:** definir el destino seguro cuando el plan se
abre directamente y no existe un contexto de navegación anterior.

## Épica GT-EP-14 — Administración de Coordinación

### GT-HU-051 — Asignar un Tutor al alumno

**Prioridad:** pendiente · **Trazabilidad:** M-12.

Como coordinador, quiero asignar un Tutor al alumno para que las revisiones
académicas correspondan a la persona responsable.

**Criterios de aceptación**

- Coordinación puede elegir y guardar un Tutor para el alumno o práctica
  correspondiente.
- La asignación queda persistida y se refleja en las vistas que dependen del
  Tutor asignado.
- Solo se aceptan tutores válidos y Coordinación autorizada puede cambiar la
  asignación.
- Un usuario no autorizado no puede modificar la asignación mediante una
  petición directa.

**Dependencias y refinamiento:** confirmar si la asignación corresponde al
alumno o a cada práctica, qué tutores son elegibles y si se permite
reasignación después de iniciar revisiones.

## Trazabilidad de solicitudes

| Fuente | Necesidad | Historias |
|---|---|---|
| M-01 | Editar perfil (excepto correo) y crear/cambiar contraseña | GT-HU-039, GT-HU-040 |
| M-02 | Ingresar como Coordinación directamente a Seguimiento | GT-HU-041 |
| M-03 | Ingresar como Supervisor directamente a Seguimiento | GT-HU-042 |
| M-04 | Filtrar o agrupar Seguimiento por estados | GT-HU-043 |
| M-05 | Abrir el plan del alumno desde «Revisar plan de trabajo» | GT-HU-044 |
| M-06 | Abrir la evaluación terminal desde su acción | GT-HU-045 |
| M-07 | Mostrar documentos iniciales y carta de término al final | GT-HU-046 |
| M-08 | Agrupar documentos en acordeón cerrado por defecto | GT-HU-047 |
| M-09 | Evitar acciones tempranas del expediente que llevan a 404 | GT-HU-048 |
| M-10 | Regresar al expediente desde la carta de presentación | GT-HU-049 |
| M-11 | Agregar regreso desde la consulta del plan de trabajo | GT-HU-050 |
| M-12 | Permitir a Coordinación asignar Tutor | GT-HU-051 |
| M-13 | Reparar el enlace de Coordinación a Rúbricas | GT-HU-052 |
| Handoff vigente, solicitud complementaria | Configurar la identidad del simulador SAML por `.env` | GT-HU-053 |

## Preguntas para refinamiento

1. ¿Qué campos del perfil pueden editarse además del correo, y cuáles son
   obligatorios?
2. ¿Qué política rige el alta/cambio de contraseña y cómo aplica a cuentas
   federadas?
3. ¿Qué estados exactos debe presentar Seguimiento del Supervisor? ¿Se elige
   un filtro o pestañas?
4. ¿Qué estados habilitan la revisión del plan, la evaluación terminal y las
   acciones del expediente?
5. ¿Qué documentos y estados exactos integran «Requisitos de término»?
6. ¿Se ocultan o se muestran deshabilitadas las acciones todavía no
   disponibles?
7. ¿Cuál es el destino seguro de regreso al abrir carta o plan sin contexto
   previo?
8. ¿La asignación de Tutor corresponde al alumno o a cada práctica? ¿Puede
   cambiarse durante el flujo?
9. ¿Debe GT-HU-053 formar parte del backlog v1.2? El handoff registra la
   solicitud, pero la capacidad ya figura como implementada y no aparece en
   `modificaciones.txt`.

## Notas de planificación

- Las historias describen trabajo candidato; no se presume aprobación,
  prioridad, estimación ni compromiso de entrega.
- Los problemas `403`/`404` deben corregirse sin ampliar permisos y con pruebas
  de acceso permitido y denegado donde corresponda.
- Las historias de navegación y regreso deben preservar el destino y el
  expediente autorizados; nunca confiar en una URL de retorno sin validarla.
- El plan de sprints se publica por separado en
  [`../sprints/plan-sprints-candidato.md`](../sprints/plan-sprints-candidato.md).
