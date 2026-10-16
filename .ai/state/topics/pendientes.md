# Pendientes

- El 2026-10-16 se completó y archivó la tarea Continuum
  `.ai/tasks/_closed/backlog-v1-2/`, asignada al rol `gestion-proyecto`, para
  preparar backlog, sprints y PDFs de v1.2 a
  partir de `docs/v1.2/planeacion/modificaciones.txt`. La copia formateada está
  en `docs/v1.2/planeacion/modificaciones.md`. El backlog candidato contiene
  15 historias y el plan cuatro sprints con perfil asignado por historia; se
  generaron los dos PDF de entregables. La persona solicitante autorizó iniciar
  Sprint 1 y confirmó que GT-HU-053 será solo verificación/documentación, sin
  desarrollo nuevo. GT-HU-052 ya tiene ruta y pruebas vigentes; queda como
  verificación QA, sin duplicar la implementación.
- Flujo v1.2 acordado: una rama desde `develop` por sprint; una tarea
  Continuum por HU asignada al perfil responsable; commit de cada HU tras
  aprobar su cierre; merge de cada sprint a `develop`. Solo después de completar
  todos los sprints, crear `v1.2` desde `develop` y mergear esa rama a `main`.
  Actualizar `AI_COLLABORATION.md` antes del cierre de versión para reflejar
  este flujo sin divergencias.
- Sprint 1 v1.2 está en `sprint/v1.2-1-acceso-navegacion-rol`, creada
  desde `develop`. GT-HU-041, GT-HU-042 y GT-HU-052 están validadas,
  aprobadas, cerradas, archivadas y comprometidas. Queda activa GT-HU-053
  (`devops-infraestructura`), para trabajarse en serie.
- GT-HU-042 dirige al Supervisor con empresa y correo verificado a
  Seguimiento por login local y SAML; el guard compartido niega el acceso
  sin empresa. Pasaron 38 tests, 223 assertions y Pint.
- GT-HU-052 verificó la ruta y el enlace de Rúbricas; Coordinación accede
  y Estudiante recibe denegación. Pasaron 11 tests y 52 assertions; su
  tarea se aprobó, cerró y archivó sin cambios de aplicación.
- Al 2026-10-15, el checkout principal `~/code/nanis` trabaja en `develop`.
  Sprint 4 se integró con el merge `bfaed9d` y se publicó con el cierre
  documental `d3f3449`. Sprint 5 se integró mediante `1e1d4ed` y se publicó
  con su cierre documental hasta `a1215b7`. `main`
  permanece en `cb5365d` con el cierre de v1.0. Sprint 5 y el cierre v1.0 son
  ancestros de `develop`. La persona solicitante aprobó el backlog
  v1.1 como base de planificación y la secuencia de cinco sprints; v1.1 aún no
  se ha integrado a `main`.
- La fuente original de solicitudes para v1.1 se conserva en
  `../../../docs/v1.1/Planeacion/Modificaciones.txt`, junto con su versión
  formateada `../../../docs/v1.1/Planeacion/modificaciones.md`.
- Tarea archivada: `.ai/tasks/_closed/planificacion-sprints-v1-1/`. Antes de
  comprometer cada sprint, resolver las preguntas abiertas correspondientes;
  no establecer fechas, duración, estimaciones ni capacidad.
- Sprint 3 se inició en `sprint/3-expediente-versiones-revision-reporte`.
  GT-HU-026, GT-HU-035 y GT-HU-036 están implementadas y cerradas; sus tareas
  Continuum están archivadas.
  Para GT-HU-026 se confirmó que el tercer punto del expediente sea Plan de
  trabajo y que cada documento tenga una insignia de estado según su flujo.
- Sprint 4 está implementado, validado e integrado en `develop`; las tareas
  GT-HU-029 (`frontend`) y GT-HU-030 (`backend`) están archivadas. La
  búsqueda se refinó para mostrar solo empresas disponibles, conservando las
  ocultas en la lista sin filtro para permitir su reactivación. Se trabajaron
  en serie. La regresión conjunta pasó con 13 tests y 83 assertions.
- Sprint 5 se inició en `sprint/5-experiencia-transversal-listas`. Sus tareas
  GT-HU-037 y GT-HU-038 están asignadas a `backend`; se resolvió ordenar por la
  fecha de la versión/revisión mostrada y se incluyeron en alcance historiales
  de versiones, administración de rúbricas y opciones de selección. GT-HU-037
  está implementada, validada (64 tests, 581 assertions, Pint) y su tarea está
  archivada en `.ai/tasks/_closed/gt-hu-037-ordenar-listas-por-modificacion/`.
  El commit de la historia es `f23d463`. GT-HU-038 está implementada y validada:
  las bandejas principales, historiales globales de versiones, rúbricas,
  encuestas y opciones de selección tienen búsqueda/paginación donde faltaban;
  Seguimiento y catálogo de Coordinación no duplican controles. Suite Laravel:
  134 tests/1077 assertions; Pint y doctor pasan, con advertencia de presupuesto
  de arranque. Su tarea está archivada; el commit de GT-HU-038 es `7fca340`,
  Sprint 5 se integró en `develop` mediante `1e1d4ed` y se publicó en `origin`.
  Preservar el cambio previo ajeno de
  `project-app/README.md`, que queda fuera de commits de historia.
- El catálogo muestra nombre, dirección, teléfono, email y persona de contacto; Coordinación da altas/bajas manualmente. La baja oculta a la empresa en nuevas solicitudes, pero conserva referencias existentes y no afecta prácticas iniciadas.
- Conciliar canales del prototipo frente a Drive/correo/Classroom/papel descritos en el procedimiento institucional 2025.
- HU-006, HU-007, HU-016 y HU-017 están implementadas y desplegadas en Sprint 4. La rúbrica de Tutor publicada en desarrollo es temporal/ficticia para demostración; falta que Coordinación publique las rúbricas institucionales. La rúbrica Supervisor continúa como borrador, por lo que HU-006 no inicia aún en demo. La práctica sintética recorre aceptación, plan y reporte aprobados.
- Sprint 5 HU-008/009/010/012/013 está integrado en `develop`; las migraciones 000015/000016/000017 están aplicadas en MySQL local. GitHub y `origin/HEAD` usan `develop` como rama predeterminada. La carta se rediseñó para emisión imprimible por Coordinación (240 horas); la migración aditiva 000017 registra la emisión y el cierre. La implementación se publicó en `origin/develop` como `4409f4a`; `develop` se integró por fast-forward en `main` hasta `6bce8e9` el 2026-10-13.
- HU-012 permite expediente completo a Coordinación, alumno propietario y Tutor asignado; Supervisor asignado solo ve presentación, aceptación, reporte aprobado y evaluación propia. HU-013 permite al Supervisor consultar solo su propia respuesta y a Coordinación todos los resultados. Las pruebas de app pasan; falta validación humana/institucional antes de usar rúbricas oficiales.
- Validar permisos/tareas de coordinador, asesor, alumno y empresa, además de estados/plazos.
- El NSS (11 dígitos) y número de cuenta (8 dígitos) son obligatorios y cifrados en el expediente: alumno propietario y Coordinación acceden al expediente; un supervisor solo los ve dentro de la carta oficial emitida para su propia empresa.
- Revisar con la autora la observación de R5 sobre el enfoque de Capacidades Humanas y definir su impacto en objetivos/indicadores.
- Confirmar privacidad, acceso, retención, uso de indicadores, cargas de trabajo y participación de los actores; los documentos los plantean como criterios, no como políticas ya acordadas.
- Refinar criterios de aceptación/dependencias antes de dimensionar historias; la persona solicitante aceptó como base los grupos P0/P1/P2 de `docs/v1.0/sprints/plan-sprints-candidato.md`.
- El seeder de desarrollo creó 20 empresas, 5 asesores, 1 supervisor, 50 alumnos y prácticas en varias etapas; está publicado en `origin/develop` como `ce38b44`. Una cuenta de Coordinación preexistente se preservó además de la cuenta demo configurada localmente; la base contiene 2 coordinadores en total. Las direcciones y la contraseña demo viven solo en el `.env` ignorado.
- No fijar duración/fechas: la persona solicitante indicó no establecer duración de sprint por ahora.

Alcance confirmado del primer prototipo: Gestión Turística solamente. Backlog candidato: `docs/v1.0/backlog/candidatos-iniciales.md`.

Seguimiento detallado: `.ai/tasks/inicio-requerimientos-backlog/`.
