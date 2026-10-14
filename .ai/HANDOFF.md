# Handoff

**Fecha:** 2026-10-14 · **Rol:** gestion-proyecto · **Rama:** `sprint/1-acceso-confiable-destinos-rol`

## Objetivo
Iniciar el Sprint 1 de v1.1 a partir de la secuencia aprobada, con una tarea
Continuum por historia y asignación explícita del rol implementador.

## Estado
- El checkout sigue dentro de `~/code/nanis`; la rama activa
  `sprint/1-acceso-confiable-destinos-rol` se creó desde el `develop` local
  para iniciar Sprint 1.
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
- Las ramas y tareas de Sprints 2–5 se crearán al iniciar cada sprint.
- No se crearon worktrees adicionales: por la decisión de mantener el trabajo
  en este checkout, las cinco historias se atenderán en serie; no hay agentes
  ejecutándolas en paralelo.
- `main` y `origin/main` permanecen en `cb5365d`, con el cierre de v1.0.
  Sprint 5 (`a11bd76`) y el cierre v1.0 son ancestros de `develop`.
- El `develop` local contiene commits documentales no publicados por delante
  de `origin/develop` (`64925ed`); no se publicaron commits.
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
  locales. Este cambio previo sigue pendiente de commit y se conservó al crear
  la rama Sprint 1; no se publicó ningún commit.

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
- Los commits nuevos están solo en el `develop` local; no se publicaron.
- La propuesta asigna las 17 historias una vez, sus enlaces resuelven y no fija
  calendario, duración, estimaciones ni capacidad.
- Las cinco tareas de Sprint 1 existen, cada una identifica su HU, criterios,
  dependencias, write-set y rol asignado por el orquestador.
- `tools/continuum doctor` encontró cero problemas críticos y dos advertencias:
  las tareas activas comparten un solo worktree y el paquete de arranque se
  estima por encima del umbral de 3500 tokens. La ejecución en serie respeta
  el checkout único.
- `docker compose config --quiet` pasó con la anulación local; `app` y el
  `nginx-proxy` compartido comparten la red `proxy`.
- `http://localhost:8001` y `http://nanis.net` respondieron `HTTP 200`; la
  segunda respuesta pasó por el proxy compartido.

## Pendiente
- Integrar el Sprint 1 a `develop`; la rama contiene un commit por historia
  cerrada.
- Iniciar los Sprints 2–5 solo después de cerrar el sprint anterior y crear
  para cada HU su tarea asignada por el orquestador.
- Decidir cuándo incluir en un commit el cambio previo de
  `project-app/README.md`; el override local está ignorado y no se publica.
