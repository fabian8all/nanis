# Handoff

**Fecha:** 2026-10-16 · **Rol:** gestion-proyecto · **Rama:** `develop`

## Objetivo
Preparar y entregar el backlog candidato y la propuesta de sprints de v1.2, con
perfiles asignados y versiones PDF, a partir de las modificaciones aportadas.

## Estado
- El checkout sigue dentro de `~/code/nanis`; la rama activa es `develop`.
- Para pruebas locales se ejecutó `DevelopmentDatasetSeeder` tras la limpieza
  de la base: 20 empresas, 50 solicitudes, 25 aceptaciones, 20 planes y 10
  reportes. Hay 59 cuentas en total (incluye dos cuentas adicionales a las 57
  del conjunto demo: 2 coordinadores y 51 alumnos); las credenciales siguen
  solo en `.env`.
- El simulador SAML ahora lee `SAML_SIMULATOR_EMAIL` del `.env` montado en el
  contenedor (`./.env:/var/www/html/.env:ro` en `docker-compose.yml`); basta
  editar el archivo, sin reiniciar. El login con contraseña no cambió.
- Se creó `docs/v1.1/entregables/` (PDF de backlog y plan de sprints) y la
  estructura de `docs/v1.2/`; la persona solicitante ya aportó el listado de
  modificaciones. v1.1 se integra en `main`.
- Sprint 4 se integró mediante el merge `bfaed9d`; su cierre documental
  `d3f3449` se publicó en `origin/develop`.
- Sprint 5 contiene GT-HU-037 y GT-HU-038. Las tareas Continuum se crearon en
  esta rama, asignadas al rol `backend`, ambas clasificadas como `large`.
- Para GT-HU-037 se confirmó ordenar por la modificación de la versión/revisión
  mostrada cuando el registro padre no cambia, y desempatar de forma estable
  por identificador.
- Para GT-HU-038 se confirmó incluir listas principales, historiales de
  versiones, listas administrativas de rúbricas y opciones de selección, sin
  duplicar controles ya existentes.
- GT-HU-037 está implementada: las listas de negocio ordenan por la
  modificación más reciente representada y desempatan por ID; las filas de
  solicitudes consideran también relaciones visibles y los historiales usan
  la fecha de la versión/revisión mostrada. Pasó la regresión dirigida
  (64 tests, 581 assertions) y Pint; la tarea Continuum está archivada en
  `.ai/tasks/_closed/gt-hu-037-ordenar-listas-por-modificacion/`. Su commit es
  `f23d463`.
- GT-HU-038 está implementada en `sprint/5-experiencia-transversal-listas`:
  se agregaron búsqueda/paginación a las bandejas, historiales globales de
  versiones, administración de rúbricas/encuestas, opciones de tutor y
  catálogo de empresas del alumno que carecían de esos controles. Las listas
  existentes de Seguimiento y catálogo de Coordinación conservan sus controles
  sin duplicados; se preservan alcance por rol, filtros, estados vacíos y la
  selección de empresa en sesión. El inventario y el handoff están en
  `.ai/tasks/_closed/gt-hu-038-buscar-paginar-listas-restantes/`.
  Pasaron 134 tests/1077 assertions, Pint, `git diff --check` y Continuum
  doctor (0 problemas críticos; advertencia del presupuesto de arranque).
  La tarea está archivada y su commit es `7fca340`; Sprint 5 se integró en
  `develop` mediante `1e1d4ed`, y la integración con su cierre documental se
  publicó en `origin/develop` hasta `a1215b7`. Preservar el cambio previo ajeno
  de `project-app/README.md`; excluirlo de commits del sprint.
- Sprint 3 se integró desde `sprint/3-expediente-versiones-revision-reporte`
  mediante el merge `c367237`; el cierre documental quedó publicado en
  `origin/develop` hasta `76a3896`.
- Sprint 1 se integró en `develop` mediante el merge `b345549`; el cierre
  documental `d1cc268` y los cambios de Sprint 1 se publicaron en `origin`.
- El backlog v1.1 contiene 17 historias (`GT-HU-022`–`GT-HU-038`), aprobado
  como base de planificación por la persona solicitante el 2026-10-14.
- La tarea `.ai/tasks/_closed/planificacion-sprints-v1-1/` cerró la secuencia
  aprobada de cinco sprints en `docs/v1.1/sprints/plan-sprints-candidato.md`.
- Sprint 1 contiene GT-HU-022, GT-HU-023, GT-HU-025, GT-HU-027 y GT-HU-034.
  Se creó una tarea Continuum por historia y se asignaron GT-HU-022 a
  `frontend` y las demás a `backend`.
- GT-HU-022 quedó implementada y validada; su tarea se cerró y archivó en
  `.ai/tasks/_closed/gt-hu-022-acceso-federado/` tras registrar su handoff.
- GT-HU-025 fue verificada: la ruta y consulta actuales ya permiten al alumno
  ver su solicitud propia y aíslan las de otros; se añadieron pruebas
  explícitas de aislamiento y de denegación para otros roles, sin cambio de
  lógica de aplicación. Su tarea se cerró y archivó en
  `.ai/tasks/_closed/gt-hu-025-consultar-solicitud-propia/`.
- GT-HU-027 redirige ahora a Coordinación a Seguimiento. Se probaron la
  autenticación y autorización del destino, y que los destinos existentes de
  Alumno, Supervisión y Tutoría se conservan. Su tarea se cerró y archivó en
  `.ai/tasks/_closed/gt-hu-027-destino-coordinacion-seguimiento/`.
- GT-HU-034 fija el regreso de Supervisión desde la carta de presentación a
  Seguimiento y conserva el destino de Tutoría. Su tarea se cerró y archivó en
  `.ai/tasks/_closed/gt-hu-034-regreso-seguro-seguimiento/`.
- GT-HU-023 usa el estado vigente de la solicitud del alumno en login local y
  SAML: sin solicitud va al catálogo; devuelta, al formulario de corrección;
  pendiente, aprobada o completada, a Seguimiento. Se ignorará y limpiará
  `url.intended` para el alumno. Los estados cancelado y rechazado no existen
  en el enum y quedan fuera de esta historia. La tarea se cerró y archivó en
  `.ai/tasks/_closed/gt-hu-023-destino-alumno-estado-practica/`.
- Sprint 2 incluye GT-HU-024, GT-HU-028, GT-HU-031, GT-HU-032 y GT-HU-033.
  Se creó y asignó una tarea Continuum por HU: GT-HU-024 a `backend`; las demás
  a `frontend`, según el alcance de cada historia.
- GT-HU-028 quedó implementada y validada: Coordinación tiene acceso visible
  al catálogo y un menú Documentos con las rutas existentes; Rúbricas conserva
  su enlace operativo. Su tarea se cerró y archivó en
  `.ai/tasks/_closed/gt-hu-028-navegacion-coordinacion/`.
- GT-HU-031 cambió los botones genéricos de Seguimiento por acciones que
  describen el destino, incluida la revisión y corrección de solicitudes. Su
  tarea se cerró y archivó en
  `.ai/tasks/_closed/gt-hu-031-rotulos-acciones-seguimiento/`.
- GT-HU-032 agrupó Cartas pendientes, Planes de trabajo, Evaluaciones y
  Encuestas en la navegación del Supervisor con sus rutas existentes. Su
  tarea se cerró y archivó en
  `.ai/tasks/_closed/gt-hu-032-documentos-supervisor/`.
- GT-HU-024 quedó implementada con la regla confirmada: solo sin solicitud o
  con solicitud `Devuelta` se puede seleccionar; `Pendiente`, `Aprobada` y
  `Completada` bloquean. Una nueva selección en estado devuelto actualiza esa
  misma solicitud. La tarea se cerró y archivó en
  `.ai/tasks/_closed/gt-hu-024-catalogo-sin-seleccion-en-tramite/`.
- Para GT-HU-033, la persona solicitante eligió dos botones separados para
  consultar la presentación y cargar la aceptación. Se implementaron en la
  etapa de aceptación con los alcances existentes por Supervisor/empresa. Su
  tarea se cerró y archivó en
  `.ai/tasks/_closed/gt-hu-033-acciones-presentacion-aceptacion/`.
- Sprint 3 contiene GT-HU-026, GT-HU-035 y GT-HU-036. Se crearon tres tareas
  Continuum en serie y se asignaron al rol `frontend`.
- Para GT-HU-026, la persona solicitante confirmó reemplazar “requisitos de
  cierre” por “Plan de trabajo” como tercer punto y mostrar una insignia con
  el estado de entrega/aprobación de cada documento. El resumen agregado de
  requisitos de cierre conserva su función actual.
- GT-HU-026 se implementó y validó en el commit `60e8fe4`; su tarea Continuum
  está cerrada y archivada.
- GT-HU-035 centralizó las etiquetas en español para los estados de versiones
  de aceptación, plan y reporte. Se validó y su tarea Continuum está cerrada y
  archivada; la etiqueta solo cambia la presentación.
- GT-HU-036 implementó observaciones por sección inicialmente ocultas con
  controles accesibles; el comentario general sigue visible y obligatorio al
  devolver. La tarea Continuum quedó cerrada y archivada.
- Sprint 4 contiene GT-HU-029 y GT-HU-030. Las tareas se asignaron a
  `frontend` y `backend`, respectivamente; ambas historias están implementadas,
  validadas y sus tareas archivadas. GT-HU-029 quedó en `52d0ac4`.
- Para GT-HU-030 se resolvió que la búsqueda incluya solo empresas disponibles
  (`is_active = true`), mientras la lista sin búsqueda conserva las ocultas
  para permitir su reactivación. La búsqueda parcial pagina 20 resultados,
  reinicia la página al cambiar y muestra un estado vacío. Se documentó en el
  backlog y el plan.
- GT-HU-029 oculta el formulario hasta iniciar un alta o edición y conserva
  campos, validaciones, permisos y cancelación sin guardar. La regresión del
  catálogo pasó con 12 tests y 74 assertions; Pint pasó para el componente.
- La regresión conjunta del catálogo pasó con 13 tests y 83 assertions;
  Pint pasó para el componente y las pruebas. El primer intento de construir
  la imagen agotó el timeout TLS contra Docker Hub, pero el reintento terminó
  y la regresión pasó también en la imagen recién reconstruida. El servicio
  `app` se recreó y `localhost:8001` y `nanis.net` responden HTTP 200.
- Las tareas del sprint se trabajan en serie dentro de este único checkout;
  no se crearon worktrees ni se inició ejecución paralela.
- Sprint 5 termina con GT-HU-037 y GT-HU-038 implementadas y validadas; ambas
  historias están comprometidas y la rama del sprint está integrada en
  `develop` (`1e1d4ed`); la integración se publicó en `origin/develop`.
- No se crearon worktrees adicionales: por la decisión de mantener el trabajo
  en este checkout, las cinco historias se atenderán en serie; no hay agentes
  ejecutándolas en paralelo.
- `main` y `origin/main` permanecen en `cb5365d`, con el cierre de v1.0.
  Sprint 5 (`a11bd76`) y el cierre v1.0 son ancestros de `develop`.
- Antes de integrar Sprint 4, `develop` y `origin/develop` estaban
  sincronizadas en `76a3896`; `main` permanece en `cb5365d`.
- La fuente original `docs/v1.1/Planeacion/Modificaciones.txt` se conserva junto
  con su versión formateada `modificaciones.md`.
- Se actualizaron 171 referencias en 52 documentos de tareas archivadas para
  que apunten a `docs/v1.0/`.
- Se preservó `project-app/.env` del checkout principal y se añadieron las
  cuatro variables locales de demo que faltaban, sin cambiar `APP_KEY` ni
  registrar valores en memoria versionada. `docker-compose.override.yml`
  permanece en el checkout principal y está ignorado por Git.
- La anulación local conecta `project-app` a la red externa `proxy`, enruta
  `nanis.net` al servicio `app` y deja el proxy propio bajo el perfil
  `standalone`. El stack está iniciado: `app` y `db` están activos y la base
  figura saludable; el proxy propio del proyecto no está iniciado.
- `project-app/README.md` documenta el uso del proxy compartido y los puertos
  locales. Este cambio previo sigue pendiente de commit; se preservó durante
  ambos sprints y no forma parte de sus commits.

- Seguimiento ya no muestra a un rol enlaces de acciones de otro rol (daban
  403): `PracticeTrackingSummary` a?ade `route_role` y la vista filtra; commit
  `ea8a713` en `develop`, a?n sin publicar en `origin`. `main` sigue en `cae5a0a`.
- El 403/`fopen` en login con contrase?a ven?a de carpetas de `storage` creadas
  como `root` por comandos `artisan`/`tinker` dentro del contenedor; se corrigi?
  con `chown -R www-data:www-data storage bootstrap/cache`. Evitar ejecutar
  artisan como root o repetir el `chown` si reaparece.
- Hallazgo abierto: con "Acceso federado UCOL", Supervisor y Tutor caen en
  `home.blade.php`, que solo tiene enlaces para Coordinador y Alumno
  (`SamlSessionController::redirectAfterAuthentication` usa `home`). Se propuso
  redirigirlos a su destino por rol como el login con contrase?a; falta aprobaci?n.
- El handoff previo registra como requerimiento adicional configurar el simulador SAML por `.env`; se
  incluyo de forma separada como GT-HU-053, pendiente de confirmar porque no figura en el archivo fuente.

## Continuidad v1.2 — 2026-10-16

- La tarea `.ai/tasks/_closed/backlog-v1-2/` se completó y archivó; estaba
  asignada al rol `gestion-proyecto`.
- La persona solicitante aportó `docs/v1.2/planeacion/modificaciones.txt`;
  permanece intacto. Su copia con formato es
  `docs/v1.2/planeacion/modificaciones.md`.
- Ya están redactados `docs/v1.2/backlog/backlog-candidato.md` (15 historias)
  y `docs/v1.2/sprints/plan-sprints-candidato.md` (cuatro sprints candidatos).
  Cada historia está asignada una sola vez y tiene un perfil responsable del
  catálogo Continuum.
- Se generaron `docs/v1.2/entregables/backlog-candidato.pdf` (7 páginas) y
  `docs/v1.2/entregables/plan-sprints-candidato.pdf` (4 páginas) desde sus
  documentos Markdown; ambos PDF son válidos. La matriz asigna un perfil
  Continuum a cada una de las 15 historias.
- El backlog y los sprints siguen siendo propuestas candidatas, no aprobadas.
  Antes de comprometer implementación, resolver las preguntas abiertas, en
  particular confirmar si GT-HU-053 —solicitud complementaria ya implementada
  según el handoff— permanece en el alcance.
- Flujo confirmado por la persona solicitante para v1.2: cada sprint tiene una
  rama creada desde `develop`; una tarea Continuum por HU, asignada al perfil
  indicado; commit de cada HU al aprobar su cierre; al terminar el sprint, su
  rama se integra en `develop`. Tras completar todos los sprints, crear una
  rama `v1.2` desde `develop` y usarla como snapshot de la versión antes de
  integrarla en `main`. Actualizar el protocolo canónico antes de ese cierre;
  no integrar v1.2 directamente desde `develop` a `main`.
- La persona solicitante pidió comenzar Sprint 1. Está pendiente decidir si
  GT-HU-053 permanece en ese sprint; las otras historias asignadas son
  GT-HU-041, GT-HU-042 y GT-HU-052.
- Revisar durante el refinamiento si el simulador SAML configurado por `.env`,
  anotado en el handoff previo, debe incluirse en el backlog.

## Worktrees y limpieza
- Git registra únicamente `~/code/nanis` como worktree.
- Los worktrees auxiliares de `main` y `develop` se retiraron; se conservaron
  sus ramas.
- El directorio físico residual `nanis-develop-integration` permanece para
  limpieza manual, según la decisión de la persona solicitante; no está
  registrado como worktree Git.

## Validación
- `main` y Sprint 5 son ancestros de `develop`.
- Las referencias antiguas `docs/{backlog,casos-de-uso,formatos,planeacion,requerimientos,sprints}/`
  ya no aparecen en `.ai/tasks`; existen los directorios destino de v1.0.
- Las 171 referencias actualizadas resuelven a rutas existentes; `git diff
  --check` pasó usando `core.whitespace=cr-at-eol` para respetar CRLF.
- Los commits de Sprint 1 y Sprint 2, sus merges y cierres documentales están
  publicados en `origin/develop`.
- La propuesta asigna las 17 historias una vez, sus enlaces resuelven y no fija
  calendario, duración, estimaciones ni capacidad.
- Las cinco tareas de Sprint 1 existen, cada una identifica su HU, criterios,
  dependencias, write-set y rol asignado por el orquestador.
- Las cinco tareas de Sprint 1 y las cinco tareas de Sprint 2 están cerradas y
  archivadas. El último doctor encontró cero problemas críticos y una
  advertencia: el paquete de arranque supera 3500 tokens.
- Las pruebas conjuntas de las superficies modificadas pasaron (47 tests,
  341 assertions); Pint pasó para los nueve archivos PHP revisados.
- La regresión conjunta de Sprint 2 pasó (41 tests, 300 assertions) y Pint pasó
  para los diez archivos PHP modificados del sprint.
- La regresión dirigida de Sprint 3 pasó (24 tests, 303 assertions); Pint pasó
  para diez archivos PHP de las historias modificadas.
- `docker compose config --quiet` pasó con la anulación local; `app` y el
  `nginx-proxy` compartido comparten la red `proxy`.
- Los servicios `app` y `db` están en ejecución; `http://localhost:8001` y
  `http://nanis.net` respondieron `HTTP 200`, este último por el proxy compartido.

## Pendiente
- Decidir cuándo incluir en un commit el cambio previo de
  `project-app/README.md`; se preserva fuera de los commits del sprint.
- Publicar `develop` (`ea8a713`) e integrar en `main`/`origin` cuando se confirme.
- Decidir el redirect/enlaces de Supervisor y Tutor tras acceso federado.
- Completar `.ai/tasks/backlog-v1-2/` y generar/revisar el backlog candidato
  v1.2 antes de planificar su implementación.
