# Propuesta de sprints — versión 1.2

**Estado:** propuesta candidata basada en el
[backlog v1.2](../backlog/backlog-candidato.md); no aprobada ni comprometida.
Los perfiles se asignan por el rol orquestador a cada historia/tarea de
implementación. No se fijan fechas, duración, estimaciones, capacidad ni
velocidad.

## Criterios de organización

- Agrupar historias por resultado de usuario y dependencias de flujo.
- Atender primero los destinos y accesos rotos, sin alterar los permisos de
  cada rol.
- Mantener cada historia en un único sprint candidato.
- Asignar a cada historia un perfil responsable del catálogo de roles
  Continuum. Esa asignación indica el perfil responsable principal; no impide
  solicitar apoyo de otros roles durante la ejecución.
- Resolver las preguntas abiertas del backlog antes de comprometer el sprint
  correspondiente.

## Secuencia candidata

| Sprint | Objetivo | Historias | Resultado esperado |
|---|---|---|---|
| 1 | Acceso directo y navegación por rol | GT-HU-041, GT-HU-042, GT-HU-052, GT-HU-053 | Coordinación y Supervisor llegan a Seguimiento, Rúbricas abre su destino autorizado y se verifica la configuración de la identidad del simulador SAML. |
| 2 | Acciones de trabajo del Supervisor | GT-HU-043, GT-HU-044, GT-HU-045 | Supervisor organiza su bandeja por estado y abre el plan o evaluación terminal de la práctica elegida, respetando asignaciones y permisos. |
| 3 | Expediente y navegación contextual | GT-HU-046, GT-HU-047, GT-HU-048, GT-HU-049, GT-HU-050 | El expediente comunica el avance, agrupa documentos y ofrece acciones válidas y regresos seguros a su contexto. |
| 4 | Perfil y administración académica | GT-HU-039, GT-HU-040, GT-HU-051 | El usuario puede mantener su perfil y contraseña, y Coordinación puede asignar un Tutor bajo reglas acordadas. |

## Perfiles asignados por el orquestador

| Sprint | Historia/tarea | Perfil responsable asignado | Motivo principal |
|---|---|---|---|
| 1 | GT-HU-041 — Abrir Seguimiento al ingresar como Coordinación | `backend` | Resolver el destino posterior a la autenticación y conservar las reglas de acceso por rol. |
| 1 | GT-HU-042 — Abrir Seguimiento al ingresar como Supervisor | `backend` | Resolver el destino posterior a la autenticación y mantener el alcance de datos asignados. |
| 1 | GT-HU-052 — Corregir el acceso de Coordinación a Rúbricas | `qa` | Confirmar por prueba que el enlace y la ruta existente siguen disponibles solo para Coordinación; no duplicar una corrección ya entregada. |
| 1 | GT-HU-053 — Configurar la identidad del simulador SAML por entorno | `devops-infraestructura` | Verificar la configuración del entorno y su documentación sin exponer credenciales. |
| 2 | GT-HU-043 — Filtrar las prácticas de Seguimiento por estado | `frontend` | Diseñar y construir el control visual de filtro o pestañas y sus estados. |
| 2 | GT-HU-044 — Abrir directamente el plan de trabajo del alumno | `backend` | Resolver destino del registro específico y validar rol/asignación. |
| 2 | GT-HU-045 — Abrir directamente la evaluación terminal | `backend` | Resolver destino y validar habilitación y autorización de la evaluación. |
| 3 | GT-HU-046 — Mostrar los documentos iniciales en los requisitos de término | `frontend` | Presentar el orden y los estados actuales en el expediente. |
| 3 | GT-HU-047 — Agrupar los documentos del expediente en un acordeón | `frontend` | Implementar la agrupación accesible y el estado colapsado inicial. |
| 3 | GT-HU-048 — Mostrar acciones del expediente cuando estén disponibles | `backend` | Definir y hacer cumplir condiciones de disponibilidad y autorización de los destinos. |
| 3 | GT-HU-049 — Volver al expediente desde la carta de presentación | `frontend` | Preservar el contexto de navegación y mostrar el regreso adecuado. |
| 3 | GT-HU-050 — Regresar desde la consulta del plan de trabajo | `frontend` | Añadir el control de retorno y conservar el contexto autorizado. |
| 4 | GT-HU-039 — Editar los datos personales del perfil | `backend` | Validar y persistir cambios propios sin permitir cambiar el correo ni editar otras cuentas. |
| 4 | GT-HU-040 — Crear o cambiar la contraseña desde el perfil | `backend` | Aplicar las reglas de autenticación y almacenamiento seguro acordadas. |
| 4 | GT-HU-051 — Asignar un Tutor al alumno | `backend` | Persistir y autorizar una relación de asignación académica. |

## Detalle por sprint

### Sprint 1 — Acceso directo y navegación por rol

**Historias y perfiles:** GT-HU-041 (`backend`), GT-HU-042 (`backend`),
GT-HU-052 (`qa`) y GT-HU-053 (`devops-infraestructura`).

**Rama:** `sprint/v1.2-1-acceso-navegacion-rol`, creada desde `develop`.

**Dependencias:** existen Seguimiento, el módulo de Rúbricas y la configuración
local del simulador SAML. Se deben verificar las rutas y permisos vigentes antes
de cambiar destinos.

**Salida:** la autenticación conduce a cada rol al espacio autorizado; el
enlace Rúbricas abre el módulo correcto. La autenticación local de Coordinación
ya redirige a Seguimiento; GT-HU-041 debe completar la redirección SAML, cuyo
controlador actualmente vuelve a `home`. Las pruebas actuales de navegación y
gestión de Rúbricas pasan, por lo que GT-HU-052 será una tarea de verificación
QA. La persona solicitante confirmó que GT-HU-053 se limita a verificar y
documentar la configuración existente por `SAML_SIMULATOR_EMAIL`, sin código
nuevo.

**Refinamiento requerido:** probar las rutas de inicio local y SAML para
Coordinación y Supervisor, preservando los destinos de Alumno y Tutor y la
autorización vigente.

### Sprint 2 — Acciones de trabajo del Supervisor

**Historias y perfiles:** GT-HU-043 (`frontend`), GT-HU-044 (`backend`) y
GT-HU-045 (`backend`).

**Dependencias:** Seguimiento, planes de trabajo y evaluación terminal ya
existen; las acciones deben resolver el registro asociado a la práctica
seleccionada, no abrir catálogos generales.

**Salida:** el Supervisor localiza sus revisiones pendientes y accede al
registro correcto, sin ampliar el alcance de empresa ni de práctica.

**Refinamiento requerido:** definir estados vigentes y su correspondencia con
aceptación, plan y evaluación; decidir entre filtro o pestañas para GT-HU-043.
La disponibilidad de acciones y el permiso del destino deben comprobarse en
servidor.

### Sprint 3 — Expediente y navegación contextual

**Historias y perfiles:** GT-HU-046 (`frontend`), GT-HU-047 (`frontend`),
GT-HU-048 (`backend`), GT-HU-049 (`frontend`) y GT-HU-050 (`frontend`).

**Dependencias:** expediente, estados de documentos, carta de presentación,
consulta del plan y permisos actuales por rol.

**Salida:** el expediente muestra el avance del proceso de forma compacta; las
acciones solo llevan a destinos válidos y los regresos no pierden el contexto
ni permiten saltar a recursos no autorizados.

**Refinamiento requerido:** acordar la composición exacta de los requisitos de
término, condiciones de disponibilidad de las acciones y destino seguro para
accesos directos sin origen conocido.

### Sprint 4 — Perfil y administración académica

**Historias y perfiles:** GT-HU-039 (`backend`), GT-HU-040 (`backend`) y
GT-HU-051 (`backend`).

**Dependencias:** autenticación, modelo de usuario, Tutoría y asociación actual
entre Tutor, alumno y práctica.

**Salida:** los cambios de perfil son propios y validados; la contraseña se
gestiona según la política acordada y Coordinación puede administrar la
asignación de Tutor sin modificar relaciones fuera de su alcance.

**Refinamiento requerido:** lista de campos editables, política de contraseña,
tratamiento de cuentas federadas, objeto al que se asigna el Tutor y reglas de
reasignación.

## Cobertura y asignación

| Sprint | Historias |
|---|---|
| 1 | GT-HU-041, GT-HU-042, GT-HU-052, GT-HU-053 |
| 2 | GT-HU-043, GT-HU-044, GT-HU-045 |
| 3 | GT-HU-046, GT-HU-047, GT-HU-048, GT-HU-049, GT-HU-050 |
| 4 | GT-HU-039, GT-HU-040, GT-HU-051 |

Las 15 historias del backlog aparecen exactamente una vez y cada una tiene un
perfil responsable asignado en la tabla anterior. Las asignaciones son
propuestas del orquestador para la planificación y pueden ajustarse al iniciar
cada tarea si el write-set real cambia; no significan que esas tareas ya se
hayan creado o iniciado.

## Pendientes antes de comprometer sprints

- Resolver las preguntas abiertas vinculadas a cada sprint en el backlog.
- GT-HU-053 se confirmó como verificación y documentación, sin desarrollo
  nuevo, pese a no aparecer en el archivo de modificaciones.
- Crear una tarea Continuum por historia al iniciar el sprint correspondiente
  y asignarla al perfil indicado, según el write-set refinado.
- No establecer fechas, duración, estimaciones, capacidad ni velocidad.
