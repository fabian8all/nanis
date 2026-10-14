# Handoff: gt-hu-007-implementacion-reporte

## Objetivo
Implementar entrega, versionado, revisión por tutor y cierre del reporte final (GT-HU-007).

## Decisiones ya confirmadas en la tarea de refinamiento
- Alumno captura secciones/campos estructurados fijos del formato institucional; no reemplaza con la carga de un DOCX.
- Disponible cuando el plan HU-005 está aprobado; sin fecha límite automática.
- Permitir borrador, correcciones iterativas hasta aprobación por tutor, historial de versiones y anexos PDF/JPG/PNG, máximo 20 MB por archivo sin límite agregado.
- Revisor: tutor/asesor asignado. Devolución con comentario general obligatorio y observaciones opcionales por sección.
- Tras aprobar, cerrar; solo Coordinación autoriza reapertura sin borrar la versión aprobada anterior.
- HU-016 usa exactamente la versión aprobada del reporte.

## Bloqueos antes de implementar
- La plantilla DOCX de referencia contiene ejemplos/valores que no se deben exponer ni versionar; una extracción XML local filtrada no recuperó toda la estructura. Se requiere outline sanitizado de secciones/campos o aprobación para definir otra estructura.
- La persona solicitante confirmó que Coordinación asignará el tutor a cada práctica. Incorporar asignación explícita y autorización limitada al tutor asignado dentro del alcance mínimo de HU-007.
- La persona solicitante aprobó la estructura simplificada: datos de la estancia (empresa, área y periodo); objetivos; actividades realizadas; resultados/aprendizajes; conclusiones; recomendaciones; anexos. Secciones narrativas como campos multilinea.

## Implementación
- Se agregó `tutor_id` a `PracticeRequest`; Coordinación asigna un usuario con rol Tutor a una práctica con aceptación y plan aprobados. El tutor no se puede cambiar tras el primer envío.
- El alumno guarda/edita borrador, adjunta PDFs/JPG/PNG privados (20 MB por archivo), y envía cuando desea. Envíos/reenvíos son versiones; las versiones enviadas conservan contenido, revisión, observaciones y anexos.
- Solo el tutor asignado revisa; devolución exige comentario general y admite observaciones por sección. Aprobación registra la versión aprobada; Coordinación puede abrir un nuevo borrador sin alterar la versión aprobada.
- Alumno propietario, tutor asignado y Coordinación consultan reportes/anexos. Se añadió autenticación/login y navegación mínima para Tutor.
- Enlace desde la solicitud aprobada cuando el plan está aprobado. La configuración PHP ya limita cada carga a 20 MB y `post_max_size` a 25 MB; la app no impone tope agregado.

## Validación
- `php artisan test`: 71 pruebas y 481 aserciones aprobadas, ejecutadas en copia temporal del contenedor, aislada de la instancia activa.
- Pint PASS en 16 archivos PHP.
- `continuum doctor`: 0 problemas críticos, 1 advertencia por costo estimado de arranque.
- No se ejecutó migración ni despliegue en el contenedor de desarrollo.

## Pendiente
- Aplicar migración `2026_10_11_000012_create_practice_reports_tables.php` mediante despliegue/reconstrucción controlada.
- HU-016 queda fuera de esta tarea; podrá consumir `PracticeReport.approved_version_id`.
- No incorporar la plantilla fuente de `../../../../docs/v1.0/formatos/`, que puede contener ejemplos con datos personales.
