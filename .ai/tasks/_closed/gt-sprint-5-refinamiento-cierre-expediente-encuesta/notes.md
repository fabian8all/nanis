# Notas de trabajo: gt-sprint-5-refinamiento-cierre-expediente-encuesta

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- La persona solicitante confirmó que la carta de término la entrega la empresa al alumno y que el alumno la carga en la plataforma; debe acreditar 240 horas, sin contador independiente.
- GT-HU-013 ya tiene alcance parcial confirmado: responde el supervisor directo cuando Coordinación lo solicita, sin plazo fijo; solo Coordinación consulta resultados y la encuesta no es una evaluación terminal.
- El procedimiento institucional 2025 describe estancias de 800 horas y canales mixtos; no trasladar automáticamente esos requisitos al prototipo.
- La carta de término cargada por el alumno acepta PDF, JPG y PNG, con tamaño máximo de 20 MB.
- Coordinación de prácticas revisa la carta cargada y determina su aceptación o devolución para corrección.
- Si se devuelve la carta, el alumno reemplaza el archivo y lo reenvía; se permiten reintentos sin límite y se conserva solo la versión vigente.
- La carta debe incluir nombre del alumno, empresa, periodo de práctica, total de 240 horas, actividades, fecha y firma del responsable empresarial.
- El expediente final incluye la carta de término aceptada, las evaluaciones terminales de Tutor y Supervisor y el reporte final; no incluye reportes intermedios.
- El alumno y Coordinación pueden consultar el expediente completo; Tutor y Supervisor solo consultan su propia evaluación y no el expediente completo.
- El expediente queda completo/cerrado cuando la carta está aceptada, ambas evaluaciones terminales están completadas y existe el reporte final.
- La encuesta de satisfacción la responde el supervisor directo dentro de la plataforma mediante enlace autenticado solicitado por Coordinación.
- Las preguntas de la encuesta son configurables por Coordinación, con escala de respuesta y comentario opcional.
- La respuesta de la encuesta es independiente y no bloquea el cierre del expediente.
- HU-010 sigue pendiente de acordar etapas/estados, reglas de avance, filtros, alertas y acciones; base documental revisada en backlog y plan candidatos.
- HU-010 muestra una fila por práctica y etapas de solicitud, aceptación, plan, evaluaciones, reporte y cierre; cada etapa muestra estado/pendientes, con filtros por estado y búsqueda por alumno/empresa.
- HU-010 envía alertas dentro de la plataforma a Coordinación y al actor que debe actuar; no se definieron alertas por correo.
- La vista de seguimiento permite abrir la práctica para completar la acción pendiente; no ejecuta aprobaciones/devoluciones directamente en la fila.
- Las respuestas de encuesta se asocian a la práctica y empresa, son visibles solo para Coordinación y se conservan mientras se mantenga el expediente; no las ven alumno ni supervisor.
- Encuesta: estados solicitada/respondida/cancelada por Coordinación; una respuesta final, sin vencimiento automático.
- Cada solicitud de encuesta conserva la versión de preguntas vigente al crearla; los cambios posteriores solo aplican a nuevas solicitudes.
- Una encuesta enviada queda cerrada e inmutable; se permite una sola respuesta final por solicitud.
- Al devolver la carta de término, Coordinación debe incluir comentario obligatorio; el alumno puede corregir y reenviar sin límite.

## Evidencia recopilada
- La propuesta Sprint 5 agrupa GT-HU-008, GT-HU-009, GT-HU-010, GT-HU-012 y GT-HU-013. La definición de HU-008/009/010/012/013 aún tiene campos por acordar en `../../../../docs/v1.0/backlog/candidatos-iniciales.md`.
- El flujo candidato confirma carta empresarial → alumno → carga digital; el sistema no tendrá contador independiente de horas.
- Rúbrica HU-006/HU-016 y reporte HU-007 están implementados. Los resultados y el reporte final deben figurar en el expediente; no agregar evaluaciones/reportes intermedios.

## Callejones sin salida (para no repetirlos)
- No usar `../../../../docs/v1.0/formatos/` como fuente versionable ni incluir archivos con ejemplos personales.
