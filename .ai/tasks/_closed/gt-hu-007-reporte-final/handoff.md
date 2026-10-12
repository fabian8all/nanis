# Cierre de tarea: gt-hu-007-reporte-final

## Objetivo
Refinar GT-HU-007 con la persona solicitante y documentar los criterios del reporte final, sin implementarlo.

## Decisiones confirmadas
- El reporte se captura en la plataforma mediante secciones y campos estructurados fijos del formato institucional; no se sustituye por subir un documento.
- El alumno puede guardar/editar el borrador y enviarlo cuando esté listo. Se habilita tras aprobarse el plan (HU-005), sin plazo automático.
- Se permiten múltiples anexos PDF/JPG/PNG, de hasta 20 MB por archivo y sin límite agregado.
- Revisa el tutor/asesor asignado; aprueba o devuelve con comentario general obligatorio y observaciones opcionales por sección.
- El alumno corrige y reenvía hasta aprobación. Se conserva cada versión, estado, anexos y observaciones; una entrega enviada no se edita directamente.
- Tras aprobación el reporte queda cerrado; solo Coordinación autoriza la reapertura, sin perder la versión aprobada previa.
- El alumno propietario, tutor asignado y Coordinación consultan el reporte y anexos.
- HU-016 requiere que HU-007 esté aprobada y queda vinculada a esa versión.
- No se incorporan reportes de avance.

## Archivos actualizados
- `docs/backlog/candidatos-iniciales.md`: criterios de HU-007 y dependencia de HU-016.
- `docs/sprints/plan-sprints-candidato.md`: captura, revisión y secuencia de cierre.
- `.ai/state/topics/pendientes.md` y `.ai/state/topics/resumen.md`: estado y pendientes.
- `.ai/HANDOFF.md`: continuidad de la tarea y siguiente historia pendiente.

## Validación
Tarea refinada y archivada. `python3 tools/continuum doctor` se ejecutó posteriormente sin problemas críticos. No se implementó código ni se copiaron formatos fuente o ejemplos con datos personales al repositorio.

## Pendientes del Sprint 4
- HU-016 se refinó e implementó en tareas posteriores del Sprint 4; su alcance quedó registrado en `.ai/tasks/_closed/gt-hu-016-evaluacion-tutor/`.
- Los límites técnicos de almacenamiento/cantidad de anexos se deben revisar durante la implementación; el requisito funcional acordado no fija un máximo agregado.
