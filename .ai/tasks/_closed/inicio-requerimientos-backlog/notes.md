# Notas de trabajo: inicio-requerimientos-backlog

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- Hipótesis de valor por validar: facilitar la gestión y seguimiento de la documentación de prácticas profesionales de estudiantes.
- Las cuatro categorías de participante mencionadas no son aún un catálogo cerrado de roles ni definen permisos.
- No hay decisiones confirmadas sobre flujo de revisión, clases de documentos, fechas, notificaciones o firma.

## Evidencia recopilada
- 2026-10-10, solicitante: “La aplicación es un sistema de gestion de las documentaciones de practicas profesionales de los alumnos.”
- El solicitante mencionó coordinador, asesor, alumno y empresa; indicó que los roles y sus tareas están pendientes de información posterior.
- Stack solicitado: Laravel/Livewire, Alpine.js, Bootstrap y MySQL; sin Vite ni Tailwind.
- Se levantaron los servicios `app`, `db` y `nginx-proxy`; la base solicitada está creada sin tablas.
- Docker Desktop no puede montar directamente el árbol WSL en esta distribución. El código se incorpora a las imágenes; reconstruir con `docker compose up --build -d` después de editar la aplicación.
- Revisión local de `../../../../docs/v1.0/planeacion/Ochoa_Ileana_R4_U2.pdf` (17 páginas): propone una plataforma web para seguimiento, evaluación y análisis de prácticas de la Licenciatura en Gestión Turística; contempla alumno, asesor académico, coordinador y representante de empresa; captura de datos/documentos/reportes/evaluaciones, estados y alertas, seguimiento del avance y exportación/impresión.
- El mismo Reto 4 registra sugerencias de usuarios para simplificar menú y navegación móvil, perfilar accesos, agrupar cartas, automatizar alertas de pendientes, mostrar estados, agregar mensajería, contador de avance y filtros.
- Revisión local de `../../../../docs/v1.0/planeacion/Ochoa_Ileana_R5_U2- observaciones.pdf` (14 páginas): enfatiza privacidad/confidencialidad, transparencia sobre datos y uso, minimización de recopilación, cautela con indicadores/automatización, criterio académico humano, co-creación, participación, cargas de trabajo y comunicación honesta de beneficios esperados.
- R5 incluye una observación insertada en el texto: revisar el enfoque de Capacidades Humanas. Requiere decisión de la autora.
- La persona solicitante confirmó: “Este proyecto será el prototipo, así que por ahora el alcance seguirá enfocado solo en Gestión Turística.”
- La persona solicitante autorizó usar las secciones/flujos del Reto 4 como base para el backlog, manteniendo las funciones como candidatas hasta validarlas.
- La persona solicitante compartió el flujo actual: solicitud con datos del alumno/empresa/puesto/fechas; coordinación crea carta de presentación; alumno la lleva a empresa; si es aceptado, empresa carga carta de aceptación; alumno crea plan; empresa evalúa durante la estancia con rúbricas (una o varias veces); alumno crea reporte final; empresa entrega carta de término y alumno la carga.
- La persona solicitante confirmó que coordinación revisa/aprueba la solicitud antes de la carta de presentación; si requiere cambios, la devuelve al alumno para que corrija y reenvíe. Los datos de la carta se precargan desde la solicitud y coordinación puede modificarlos. Comentarios/estados, número de reenvíos y auditoría de cambios siguen pendientes.
- Se documentó la secuencia en `../../../../docs/v1.0/casos-de-uso/flujo-candidato-practica.md`; backlog refinado por etapas con historias y preguntas pendientes en `../../../../docs/v1.0/backlog/candidatos-iniciales.md`.
- 2026-10-10, revisión de `../../../../docs/v1.0/planeacion/Cedula_de_proceso_enero_2025.drawio.pdf` y `1738086627_Procedimiento-Enero_2025_Atte_a_Obs_27012025.docx`: documentos institucionales describen charla de coordinación para octavo semestre, lista de convenios, solicitud y carta de no responsiva; selección de empresa y aceptación verbal antes de solicitar carta; reubicación tras 30 días sin aceptación; carta de presentación elaborada por coordinación; carta de aceptación entregada por estudiante y archivada por coordinación; asignación de tutor; revisión iterativa del reporte; evaluaciones del supervisor y del tutor; cuestionario de satisfacción empresarial y cierre con al menos 800 horas/carta de término. Se registraron también los canales Drive/correo/Classroom/papel en el caso de uso.
- Los documentos 2025 difieren de la secuencia inicial compartida: orden previo de aceptación verbal, entrega de carta de aceptación, y evaluación(es) con rúbricas frente a fichas de supervisor y tutor. No sobrescribir la secuencia confirmada ni convertir los documentos fuente en requisitos sin conciliar.
- El procedimiento pasa de la actividad 27 a la 33 en el texto revisado; confirmar si faltan actividades 28–32 o si la numeración es intencional. Las 800 horas e indicadores publicados son condiciones/métricas de la fuente, pero su aplicación al prototipo sigue pendiente.
- El flujo y backlog fueron actualizados para conservar ambas fuentes y añadir historias candidatas de tutor, fichas y satisfacción; no se asignaron prioridades ni compromisos.
- 2026-10-10, decisión de la persona solicitante: tomar el orden que compartió inicialmente para el prototipo (solicitud digital → revisión de coordinación → carta de presentación → respuesta de empresa), no exigir la aceptación verbal previa que describe el procedimiento 2025. Requisito confirmado adicional: la plataforma mostrará un listado de empresas con convenio vigente para que el alumno elija; quedan por definir propietario, fuente, actualización y regla de vigencia del catálogo.
- 2026-10-10, aclaración de la persona solicitante: coordinación capturará y actualizará las empresas, convenios y vigencias directamente en la plataforma. El caso de uso y el backlog incluyen historias para consultar el catálogo como alumno y administrarlo como coordinación; faltan campos y política cuando un convenio vence durante una solicitud/estancia.
- 2026-10-10, decisión de la persona solicitante: al vencer un convenio, la empresa se oculta para nuevas solicitudes y no se afectan estancias ya iniciadas. Sigue pendiente el caso intermedio en que el alumno ya seleccionó la empresa, pero la estancia aún no ha comenzado.
- 2026-10-10, decisión de la persona solicitante para el caso intermedio: si el convenio vence después de seleccionar la empresa pero antes de iniciar la estancia, coordinación debe revisar y autorizar el caso antes de continuar.
- 2026-10-10, actualización posterior de alcance por la persona solicitante: el prototipo cubrirá prácticas operativas y de mandos medios, no estancias profesionales. Se concluyen en 240 horas; no habrá evaluaciones ni reportes intermedios. Esta decisión prevalece sobre la anterior secuencia candidata de estancia, sus evaluaciones/rúbricas y el procedimiento institucional de estancias con 800 horas. Se conserva el reporte final mencionado previamente; sus reglas de revisión siguen pendientes.
- 2026-10-10, precisión posterior de la persona solicitante: sí habrá una evaluación al término y un reporte final; lo excluido son las evaluaciones y reportes intermedios propios de las estancias. La nota previa de “sin evaluaciones” se actualiza con esta precisión. Falta identificar quién aplica la evaluación y con qué instrumento.

## Callejones sin salida (para no repetirlos)
- No inventar permisos, estados, documentos obligatorios ni reglas académicas antes de recibir y validar la definición del proyecto.
- Tomar prototipos y recomendaciones de R4 como propuestas para validación, no como especificación aprobada ni lista final de funcionalidades.
- No convertir R5 en reglas de cumplimiento concretas sin confirmar normativa institucional/aplicable y responsables.
- No extender el primer prototipo a otras licenciaturas.
- No asumir que empresa sube la carta de término; en la descripción la empresa la entrega al alumno y este es quien la carga.
- Confirmar justificación y controles para tratar el NSS antes de convertirlo en campo requerido.
