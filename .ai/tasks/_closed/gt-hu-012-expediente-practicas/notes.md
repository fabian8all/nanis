# Notas de trabajo: gt-hu-012-expediente-practicas

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- La vista de expediente será solo para Coordinación y alumno propietario; no se añadirá acceso a Tutor/Supervisor fuera de sus vistas de resultado existentes.
- La página podrá mostrar un expediente incompleto con sus pendientes; el cierre se calcula de los cuatro criterios acordados y no depende de HU-013.

## Evidencia recopilada
- `PracticeRequest` relaciona carta de término, reporte, evaluación del tutor y evaluación del supervisor en `project-app/app/Models/PracticeRequest.php`.
- `PracticeTerminationLetterFileController` permite descarga privada solo a Coordinación y al alumno propietario.
- `PracticeReportAttachmentController` permite Coordinación/alumno propietario y tutor asignado; excluir Tutor de la vista de expediente no requiere cambiar este acceso existente para anexos.
- Las rutas de resultado de evaluaciones ya están separadas por rol en `project-app/routes/web.php`.
- La versión aprobada del reporte se obtiene por `practiceReport.approvedVersion`; incluye anexos mediante `attachments`.
- La tarea HU-012 se creó y reclamó; su implementación y pruebas ya terminaron.
- El `PracticeTrackingSummary` ya calcula el cierre con los cuatro criterios acordados. Se reutilizará/extenderá para exponerlos individualmente en el expediente, evitando una segunda regla de negocio.
- El `nginx-proxy` compartido ya sirve `http://nanis.net`; la base de desarrollo nueva está migrada y el `.env` local conserva su APP_KEY.

## Callejones sin salida (para no repetirlos)
- No reutilizar las páginas de seguimiento o carga de documentos como si fueran el expediente consolidado.
- No exponer el expediente completo a tutor o supervisor; mantener sus accesos de evaluación por separado.
