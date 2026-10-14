# Handoff

**Fecha:** 2026-10-14 · **Rol:** gestion-proyecto · **Rama:** `develop`

## Objetivo
Cerrar Sprint 3 de v1.1, integrado y validado en `develop`.

## Estado
- El checkout sigue dentro de `~/code/nanis`; la rama activa es `develop`.
- Sprint 3 se integró desde `sprint/3-expediente-versiones-revision-reporte`
  mediante el merge `c367237`. Su publicación en `origin/develop` se realiza
  como parte de este cierre.
- Sprint 2 y su cierre documental están publicados en `origin/develop` hasta
  `bea2c2e`.
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
- Las tareas del sprint se trabajan en serie dentro de este único checkout;
  no se crearon worktrees ni se inició ejecución paralela.
- Las ramas y tareas de Sprints 4–5 se crearán al iniciar cada sprint.
- No se crearon worktrees adicionales: por la decisión de mantener el trabajo
  en este checkout, las cinco historias se atenderán en serie; no hay agentes
  ejecutándolas en paralelo.
- `main` y `origin/main` permanecen en `cb5365d`, con el cierre de v1.0.
  Sprint 5 (`a11bd76`) y el cierre v1.0 son ancestros de `develop`.
- `origin/develop` incluye `bea2c2e`; el merge de Sprint 2 y su cierre
  documental están publicados.
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
- Sprint 3 está integrado y validado en `develop`; publicar el merge y el
  cierre documental en `origin/develop` como último paso.
- Decidir cuándo incluir en un commit el cambio previo de
  `project-app/README.md`; se preserva fuera de los commits del sprint.
