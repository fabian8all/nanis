# Handoff

**Fecha:** 2026-10-14 · **Rol:** gestion-proyecto · **Rama:** `sprint/2-navegacion-acciones-operativas`

## Objetivo
Iniciar Sprint 2 de v1.1 desde `develop`, con una tarea Continuum por historia
asignada por el orquestador.

## Estado
- El checkout sigue dentro de `~/code/nanis`; la rama activa es
  `sprint/2-navegacion-acciones-operativas`, creada desde el `develop`
  sincronizado con `origin/develop` en `d1cc268`.
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
- GT-HU-024 requiere confirmar si una solicitud completada permite una nueva
  selección, además del estado devuelto. GT-HU-033 requiere elegir botones
  separados o un menú desplegable. No se implementarán estas dos historias
  hasta resolver sus decisiones pendientes.
- Las tareas del sprint se trabajan en serie dentro de este único checkout;
  no se crearon worktrees ni se inició ejecución paralela.
- Las ramas y tareas de Sprints 3–5 se crearán al iniciar cada sprint.
- No se crearon worktrees adicionales: por la decisión de mantener el trabajo
  en este checkout, las cinco historias se atenderán en serie; no hay agentes
  ejecutándolas en paralelo.
- `main` y `origin/main` permanecen en `cb5365d`, con el cierre de v1.0.
  Sprint 5 (`a11bd76`) y el cierre v1.0 son ancestros de `develop`.
- `origin/develop` está sincronizado hasta `d1cc268`. La rama de Sprint 2 aún
  no se ha publicado.
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
  locales. Este cambio previo sigue pendiente de commit; se preservó al
  integrar Sprint 1 y no forma parte del merge.

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
- Los cinco commits de historia, el merge de Sprint 1 y el cierre documental
  están publicados en `origin/develop`.
- La propuesta asigna las 17 historias una vez, sus enlaces resuelven y no fija
  calendario, duración, estimaciones ni capacidad.
- Las cinco tareas de Sprint 1 existen, cada una identifica su HU, criterios,
  dependencias, write-set y rol asignado por el orquestador.
- Las cinco tareas de Sprint 1 están cerradas y archivadas. En Sprint 2 quedan
  tres tareas activas en este worktree porque se ejecutan en serie; el último
  doctor registró esa advertencia y que el paquete de arranque supera 3500
  tokens, sin problemas críticos.
- Las pruebas conjuntas de las superficies modificadas pasaron (47 tests,
  341 assertions); Pint pasó para los nueve archivos PHP revisados.
- `docker compose config --quiet` pasó con la anulación local; `app` y el
  `nginx-proxy` compartido comparten la red `proxy`.
- Los servicios `app` y `db` están en ejecución; `http://localhost:8001` y
  `http://nanis.net` respondieron `HTTP 200`, este último por el proxy compartido.

## Pendiente
- Resolver las dos preguntas de refinamiento de Sprint 2 antes de implementar
  GT-HU-024 y GT-HU-033.
- Continuar en serie con las historias pendientes; completar las cinco historias
  con un commit por tarea, luego integrar la rama a `develop` y validar el
  sprint.
- Decidir cuándo incluir en un commit el cambio previo de
  `project-app/README.md`; el override local está ignorado y no se publica.
