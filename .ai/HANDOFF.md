# Handoff: nanis

**Fecha:** 2026-10-11 · **Rol:** backend

## Objetivo
Iniciar una nueva aplicación y establecer Continuum para descubrir requisitos, diseñar backlog y plan de trabajo.

## Archivos revisados
- `AI_COLLABORATION.md`, `.ai/config.json`, `.ai/state/estado-dev.md`, `.ai/HANDOFF.md`, catálogo `gestion-proyecto`, fuentes de planeación iniciales y procedimiento/cédula institucional 2025.

## Archivos modificados
- `project-app/`: esqueleto Laravel/Livewire, Docker Compose con MySQL, página inicial Bootstrap y guía local.
- `project-app/docker/nginx-proxy.conf` y `Dockerfile.nginx`: reverse proxy local para `nanis.net`.
- `docs/`: índice, carpetas, visión inicial, backlog candidato, plan de trabajo por etapas y documentos fuente en `docs/planeacion/`.
- `.ai/tasks/_closed/inicio-requerimientos-backlog/`: backlog candidato entregado y decisiones de alcance documentadas; criterios, prioridades y estimaciones no se presentaron como aprobados.
- `.ai/tasks/_closed/planificacion-sprints-agentes/`: tarea de organización de sprints cerrada; plan candidato y roles asignados como base, sin fechas ni estimaciones.
- `.ai/state/`: índice, resumen de stack y pendientes iniciales.
- `docs/casos-de-uso/flujo-candidato-practica.md`, `docs/backlog/candidatos-iniciales.md` y notas de tarea: actualizan el flujo de solicitud y el cierre a 240 horas.

## Decisiones
- Base generada con Laravel 13 y Livewire 4; PHP 8.4, MySQL 8.4, Bootstrap CDN y Alpine incluido por Livewire. No se usan Vite ni Tailwind.
- Las migraciones no corren automáticamente; ejecutar `docker compose exec app php artisan migrate` cuando corresponda.
- Compose requiere credenciales locales definidas en el `.env` ignorado por Git; no se añadieron claves/contraseñas al repositorio.
- La app y el proxy se construyen desde el contexto enviado por Docker Compose; no usan bind mounts del árbol WSL.
- Propósito inicial informado por la persona solicitante: gestionar la documentación de prácticas profesionales de estudiantes. Se mencionaron coordinador, asesor, alumno y empresa; funciones y permisos no están definidos.
- R4 concreta una propuesta para la Licenciatura en Gestión Turística y enumera capacidades potenciales: expedientes/documentos, reportes, evaluación, alertas, estados, seguimiento, exportación, perfiles, mensajería y filtros.
- R5 agrega consideraciones para privacidad/minimización, transparencia, límites de automatización e indicadores, supervisión académica, co-creación y cargas de trabajo. Incluye una observación editorial pendiente sobre el enfoque de Capacidades Humanas.
- La persona solicitante confirmó que el proyecto es un prototipo enfocado solo en Gestión Turística y aprobó usar las secciones/flujos del Reto 4 como base del backlog candidato.
- La persona solicitante describió inicialmente solicitud, carta de presentación, aceptación de empresa, plan, evaluación, reporte final y carta de término; esta secuencia fue afinada después con el alcance específico de prácticas operativas y de mandos medios.
- Confirmó que coordinación revisa/aprueba solicitudes antes de la carta; esta precarga los datos de solicitud y coordinación puede modificarlos. Corrección/rechazo y trazabilidad de cambios siguen pendientes.
- La cédula/procedimiento institucional 2025 añade aceptación verbal de empresa previa a la solicitud de carta, carta de no responsiva, coordinación asigna tutor, reporte iterativo revisado por tutor, fichas de supervisor y tutor, encuesta de satisfacción y requisito documental de 800 horas. El orden de aceptación y solicitud difiere del flujo compartido inicialmente; la persona solicitante confirmó mantener para el prototipo el flujo digital compartido inicialmente.
- La persona solicitante confirmó usar el flujo digital compartido inicialmente: solicitud → revisión de coordinación → carta de presentación → respuesta de empresa; la aceptación verbal previa de la fuente 2025 no es condición de entrada. También confirmó un catálogo de empresas con convenio vigente en la plataforma, capturado y actualizado por coordinación. Al vencer un convenio se oculta para nuevas solicitudes, no afecta estancias ya iniciadas y, si vence entre selección e inicio, coordinación revisa y autoriza antes de continuar.
- Alcance vigente: prácticas operativas y de mandos medios, cierre a las 240 horas, evaluación final del supervisor y evaluación final separada del tutor (que también evalúa el reporte), sin evaluaciones ni reportes intermedios. Coordinación podrá modificar ambas rúbricas; cada evaluación conservará la versión aplicada. Falta definir qué partes son editables y quién publica cambios. La carta de término acredita 240 horas sin contador independiente.
- `docs/casos-de-uso/flujo-candidato-practica.md` conserva la secuencia y sus incógnitas; `docs/backlog/candidatos-iniciales.md` la desglosa en historias. Grupos P0/P1/P2 y secuencia de cinco sprints aceptados como base, sin estimaciones/fechas comprometidas.
- Coordinación podrá modificar las dos rúbricas de evaluación; cada evaluación conserva la versión aplicada. El formato de tutor incluye evaluación del reporte. La encuesta de satisfacción a empresa (GT-HU-013) es distinta de ambas, la responde el supervisor cuando Coordinación la solicite y solo Coordinación consulta las respuestas; faltan definir preguntas, canal y resguardo.

## Validación
- Tras los cambios de HU-019, `docker compose up --build -d` reconstruye la aplicación; `http://nanis.net/login` y `/auth/saml/metadata` responden HTTP 200.
- `php artisan test --no-coverage`: 29 pruebas y 128 aserciones aprobadas, incluyendo HU-014; Pint pasa en los archivos PHP tocados por HU-014.
- `python3 tools/continuum doctor` en WSL: 0 problemas críticos y 2 advertencias (dos tareas activas en el mismo worktree, trabajo secuencial; y costo estimado del paquete de arranque).

## Riesgos / pendientes
- El objetivo general ya se conoce; faltan reglas, documentos, flujo y responsabilidades. El backlog conserva solo hipótesis no aprobadas. La revisión encontró actividades 1–27 y luego 33–36 en el procedimiento; validar si faltan 28–32.
- Docker Desktop no tiene integración WSL habilitada; el código queda dentro de la imagen. Reconstruir con `docker compose up --build -d` para incorporar cambios.

## Cierre de planificación y siguiente paso
- `planificacion-sprints-agentes` se cierra con el plan candidato de cinco sprints, la secuencia y prioridades aceptadas como base; se conserva sin calendario ni estimaciones.
- El handoff detallado y los pendientes quedaron archivados en `.ai/tasks/_closed/planificacion-sprints-agentes/`.
- Se añadió a `AI_COLLABORATION.md` §2.1 el flujo duradero solicitado: tarea Continuum por historia, asignación por agente orquestador, commit al cerrar cada historia, una rama por sprint desde `develop`, merge de sprint a `develop` y merge final a `main`.
- Se creó `develop` desde `main`; el último commit en ambas ramas es `c2892d5`.
- Sprint 1 está en `sprint/1-catalogo-solicitud`, último commit `7ac394b`. `gt-hu-015-empresas-convenios` (backend) cerrada y archivada tras implementar el catálogo: Coordinación registra nombre, dirección, teléfono, email y contacto; altas/bajas son manuales y una baja oculta a la empresa para nuevas solicitudes conservando referencias existentes. El login local de Coordinación y el comando Artisan interactivo para crear la cuenta inicial no versionan credenciales.
- HU-015 implementada y comprometida en `54c0edf` en `sprint/1-catalogo-solicitud`: limitador de intentos, rutas/middleware protegidos, CRUD Livewire y migraciones aplicadas a MySQL. PHPUnit: 11 pruebas y 41 aserciones; Pint PASS para 13 archivos PHP; `/login` responde HTTP 200. No se creó una cuenta real de Coordinación.
- Se cerró HU-019 (`backend`) en `18480ee`; login SAML con simulador local, rol único y aprovisionamiento por `uCorreo`. La configuración real del IDP queda para después. HU-014 (`frontend`) implementó y archivó `/alumno/empresas`: muestra empresas activas ordenadas, sus datos y permite selección de alumno. El ID queda en sesión como `practice.selected_company_id` para HU-001; no se escribe en el perfil ni se crea la solicitud. Si una empresa deja de estar activa se descarta la selección. Se añadió navegación contextual por rol y logout del alumno. La suite tiene 29 pruebas/128 aserciones aprobadas y Pint pasa para los PHP tocados.
- Las fuentes de `docs/formatos/` siguen fuera del commit por contener ejemplos rellenados con datos personales.
- `ajuste-sprints-formatos-y-evaluaciones` quedó cerrada y archivada en `.ai/tasks/_closed/`; sus cambios documentales se incluyen en el commit de la tarea. El plan agrega HU-016 (evaluación del tutor), HU-017 (rúbricas editables/versionadas) y HU-018 (aceptación e impresión de no responsiva).
- Se confirmó que el alumno firma físicamente la carta impresa y el sistema no conserva copia firmada.
- `docs/formatos/` contiene archivos de fuente aún no versionados; algunas imágenes tienen ejemplos rellenados con datos personales. No copiarlos a memoria ni incluirlos en commits sin saneamiento.
- Se inició `gt-hu-001-solicitud-practica`, asignada a `backend` en `sprint/1-catalogo-solicitud`. Criterios confirmados: campos acordados obligatorios (correo UCOL, grado/grupo, inicio, lugar, destinatario, puesto, término), fin posterior al inicio, empresa activa al enviar, máximo una solicitud en trámite, solo editable tras devolución de Coordinación y confirmación sin folio. El alumno acepta la carta de no responsiva; se imprime con la fecha del día, se completa a mano y se firma físicamente; no se conserva copia firmada. La persona solicitante aclaró que el documento tiene espacios para llenado manuscrito; no modificar ni versionar `docs/formatos/`.
- HU-001 completada en `sprint/1-catalogo-solicitud`: formulario Livewire y persistencia del correo UCOL, grado/grupo, empresa, fechas, ubicación, destinatario y puesto; validación de vigencia de empresa y fechas; una solicitud abierta por alumno; solo editable tras devolución. La carta de no responsiva conserva su texto fuente y reemplaza solo la fecha por la fecha de impresión; se completa y firma físicamente, sin conservar copia firmada. La suite dirigida pasa (7 pruebas/46 aserciones), Pint pasa en los archivos PHP tocados y `/alumno/solicitud` redirige a autenticación (HTTP 302). Para mantener la suite completa válida con la nueva migración se ajustó `UserRoleMigrationTest` para revertir la migración de roles explícitamente, no por `--step=1`. GT-HU-002 aún debe implementar la devolución administrativa.
- Sprint 1 integrado por fast-forward en `develop` hasta `78b5b41`; rama activa `sprint/2-revision-cartas`. Se abrió `gt-hu-002-revision-solicitud` (backend). Criterios confirmados: aprobar o devolver, sin rechazo definitivo; comentario obligatorio al devolver e historial de revisiones; solicitud original inmutable ante ajustes de carta; Coordinación solo cambia destinatario, puesto, lugar y fechas; carta oficial con folio anual `CP-AAAA-0001`, se imprime para firma física. HU-003 dará acceso/descarga al alumno. Se preservan intactos cambios ajenos preexistentes en archivos de Continuum, hooks y formatos.
- El formato de presentación incluye No. de IMSS y número de cuenta; el usuario confirmó que HU-001 los solicite obligatorios (NSS de 11 dígitos y cuenta de 8) y que se conserven cifrados en el expediente, accesibles solo al alumno dueño y Coordinación. HU-002 los usará solo en la carta impresa, sin presentarlos en la bandeja ni en la bitácora de revisiones; la cuenta se imprime `0000-0000`.

## Continuidad actual — GT-HU-002
- GT-HU-002 está implementada en `sprint/2-revision-cartas`; Coordinación revisa pendientes, devuelve con comentario e historial o aprueba para generar/imprimir una carta con copia editable y folio anual. NSS y cuenta se mantienen cifrados y fuera de bandeja, revisión e historial.
- Validación final: 42 pruebas/228 aserciones aprobadas; Pint `--test` pasa para los 12 archivos PHP modificados. Migraciones HU-002 aplicadas al MySQL local.
- `.ai/tasks/_closed/gt-hu-002-revision-solicitud/handoff.md` contiene el handoff detallado. HU-002 quedó comprometida como `c2dd745` en la rama del sprint; no integrar aún a `develop`.
- Preservar sin cambios los archivos preexistentes ajenos: `.continuum/tools/continuum`, `.githooks/pre-commit`, `tools/continuum`, fuentes de `docs/formatos/` y archivos `Zone.Identifier`.
- Siguiente historia: GT-HU-003, consulta/descarga de la carta por el alumno.

## Continuidad actual — GT-HU-003
- GT-HU-003 está activa en `sprint/2-revision-cartas`, asignada a `backend`. Decisión confirmada: el alumno propietario puede consultar e imprimir/guardar como PDF su carta aprobada; no se registran descargas ni entrega.
- Implementación lista para revisar/cerrar: enlace desde solicitud aprobada, vista imprimible compartida con Coordinación, autorización por rol/propiedad y 404 para cartas de solicitudes no aprobadas.
- Validación: suite completa 45 pruebas/246 aserciones; Pint `--test` pasa en 6 archivos PHP. Contenedor de desarrollo reconstruido; rutas estudiantiles protegidas respondieron 302 sin sesión.
- `.ai/tasks/_closed/gt-hu-003-consulta-carta-alumno/handoff.md` contiene el handoff y las decisiones de HU-003. Comprometer la historia en la rama del sprint; HU-004 sigue pendiente.
- Se preservan las modificaciones y formatos fuente ya presentes en el worktree, fuera del alcance de GT-HU-003.
