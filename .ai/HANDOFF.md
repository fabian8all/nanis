# Handoff: nanis

**Fecha:** 2026-10-10 · **Rol:** gestion-proyecto

## Objetivo
Iniciar una nueva aplicación y establecer Continuum para descubrir requisitos, diseñar backlog y plan de trabajo.

## Archivos revisados
- `AI_COLLABORATION.md`, `.ai/config.json`, `.ai/state/estado-dev.md`, `.ai/HANDOFF.md`, catálogo `gestion-proyecto`, fuentes de planeación iniciales y procedimiento/cédula institucional 2025.

## Archivos modificados
- `project-app/`: esqueleto Laravel/Livewire, Docker Compose con MySQL, página inicial Bootstrap y guía local.
- `project-app/docker/nginx-proxy.conf` y `Dockerfile.nginx`: reverse proxy local para `nanis.net`.
- `docs/`: índice, carpetas, visión inicial, backlog candidato, plan de trabajo por etapas y documentos fuente en `docs/planeacion/`.
- `.ai/tasks/_closed/inicio-requerimientos-backlog/`: backlog candidato entregado y decisiones de alcance documentadas; criterios, prioridades y estimaciones no se presentaron como aprobados.
- `.ai/tasks/planificacion-sprints-agentes/`: nueva tarea activa large asignada a `gestion-proyecto` para priorizar backlog, organizar sprints candidatos y mapear historias a roles/agentes.
- `.ai/state/`: índice, resumen de stack y pendientes iniciales.
- `docs/casos-de-uso/flujo-candidato-practica.md`, `docs/backlog/candidatos-iniciales.md` y notas de tarea: integran el flujo confirmado y el alcance de 240 horas, una evaluación y un reporte final, sin actividades intermedias.

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
- Aclaración más reciente de alcance: el prototipo es para prácticas operativas y de mandos medios, no estancias profesionales. Terminan a las 240 horas e incluyen una evaluación al término aplicada por la empresa/supervisor con rúbrica específica que definirá y aprobará Coordinación de prácticas, y un reporte final revisado por el tutor/asesor académico, con correcciones y reenvíos hasta su aprobación; no hay evaluaciones ni reportes intermedios. La carta de término registrada acredita las 240 horas; no se necesita contador independiente. Faltan criterios y formato de rúbrica, formato/validación de carta y criterios/estados del reporte. Las 800 horas de estancias no aplican.
- `docs/casos-de-uso/flujo-candidato-practica.md` conserva la secuencia y sus incógnitas; `docs/backlog/candidatos-iniciales.md` la desglosa en historias. Grupos P0/P1/P2 y secuencia de cinco sprints aceptados como base, sin estimaciones/fechas comprometidas.
- Coordinación de prácticas definirá y aprobará la rúbrica terminal. La persona solicitante confirmó incluir la encuesta de satisfacción a empresa (GT-HU-013), separada de la evaluación terminal, respondida por el supervisor directo cuando coordinación la solicite sin fecha fija, y con acceso a respuestas solo para coordinación. Aún no está definido quién diseñará/aprobará el cuestionario, el canal ni el resguardo de respuestas.

## Validación
- `docker compose up --build -d`: app, MySQL y `nginx-proxy` iniciados; MySQL healthy.
- `http://nanis.net`: HTTP 200. Base `project` accesible con el usuario solicitado y 0 tablas; no se ejecutaron migraciones.
- `php artisan test`: 2 pruebas aprobadas. `tools/continuum doctor`: 0 problemas críticos y una advertencia sobre el costo del contexto de arranque.

## Riesgos / pendientes
- El objetivo general ya se conoce; faltan reglas, documentos, flujo y responsabilidades. El backlog conserva solo hipótesis no aprobadas. La revisión encontró actividades 1–27 y luego 33–36 en el procedimiento; validar si faltan 28–32.
- Docker Desktop no tiene integración WSL habilitada; el código queda dentro de la imagen. Reconstruir con `docker compose up --build -d` para incorporar cambios.

## Cierre de planificación y siguiente paso
- `planificacion-sprints-agentes` se cierra con el plan candidato de cinco sprints, la secuencia y prioridades aceptadas como base; se conserva sin calendario ni estimaciones.
- El handoff detallado y los pendientes se archivarán en `.ai/tasks/_closed/planificacion-sprints-agentes/`.
- Se añadió a `AI_COLLABORATION.md` §2.1 el flujo duradero solicitado: tarea Continuum por historia, asignación por agente orquestador, commit al cerrar cada historia, una rama por sprint desde `develop`, merge de sprint a `develop` y merge final a `main`.
- Antes del primer sprint, crear `develop` desde `main` si aún no existe; después abrir una tarea Continuum por cada historia del sprint 1 y asignarla según las necesidades del trabajo.
