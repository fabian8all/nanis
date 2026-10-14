# Pendientes

- Al 2026-10-14, el checkout principal `~/code/nanis` trabaja en
  `sprint/5-experiencia-transversal-listas`, creada desde `develop`. Sprint 4
  se integró con el merge `bfaed9d` y se publicó con el cierre documental
  `d3f3449`; `develop` y `origin/develop` están sincronizadas. `main` permanece en
  `cb5365d` con el cierre de v1.0. Sprint 5 y el cierre v1.0 son ancestros de
  `develop`. La persona solicitante aprobó el backlog
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
  GT-HU-038 aún requiere inventario de campos de búsqueda y controles
  existentes.
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
