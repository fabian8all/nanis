# Notas de trabajo: gt-hu-007-implementacion-reporte

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Decisiones confirmadas
- La persona solicitante aprobó el 2026-10-11 la estructura simplificada: datos de la estancia (empresa, área y periodo), objetivos, actividades realizadas, resultados/aprendizajes, conclusiones, recomendaciones y anexos. Las secciones narrativas se capturan en campos de texto multilinea.
- Coordinación asigna explícitamente el tutor a cada práctica; incorporar la relación y el flujo mínimo de asignación.

## Evidencia recopilada
- Requisitos aprobados en `.ai/tasks/_closed/gt-hu-007-reporte-final/handoff.md`: captura estructurada fija; anexos PDF/JPG/PNG hasta 20 MB cada uno, sin máximo agregado; disponible tras plan aprobado; tutor revisa/devolver con comentario general obligatorio; historial; reapertura solo Coordinación; consulta por alumno, tutor asignado y Coordinación.
- HU-016 se activa tras HU-007 aprobado y fija exactamente la versión aprobada: `.ai/tasks/_closed/gt-hu-016-evaluacion-tutor/handoff.md`.
- Al iniciar, `UserRole` contenía Tutor, pero `PracticeRequest` no tenía relación ni flujo de asignación. HU-007 agrega `tutor_id`, gestión mínima de asignación por Coordinación y acceso de revisión limitado al tutor asignado.
- El DOCX “Formato Reporte_ESTANCIA PROFESIONAL” está poblado/no extraíble completamente; la extracción XML local filtrada solo identificó con seguridad etiquetas genéricas como conclusiones/anexos y no permitió recuperar todas las secciones/campos. Ningún contenido de ejemplo se transcribió.
- La persona solicitante aprobó la propuesta de estructura simplificada y confirmó que Coordinación asignará al tutor.
- Implementación validada en la copia temporal del contenedor: suite completa 71 pruebas/481 aserciones, y Pint PASS en 16 archivos PHP. El worktree no se monta directamente en Docker Desktop, por eso las pruebas se ejecutaron dentro de `/tmp` en el contenedor, sin alterar `/var/www/html`.
- Pendiente aplicar migración `2026_10_11_000012` y desplegar en el servicio de desarrollo; esta tarea no realizó despliegue.

## Callejones sin salida (para no repetirlos)
- No usar el subagente ni imprimir todo el XML binario/texto del DOCX; ya se intentó una extracción local limitada con lista blanca y la estructura sigue incompleta.
