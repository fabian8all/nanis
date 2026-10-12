# Handoff: gt-sprint-5-refinamiento-cierre-expediente-encuesta

**Fecha:** 2026-10-12 · **Rol:** gestion-proyecto

## Objetivo
Refinar criterios candidatos de Sprint 5 con confirmación de la persona solicitante, sin implementar historias.

## Archivos modificados
- `docs/backlog/candidatos-iniciales.md`: criterios de GT-HU-008/009/010/012/013.
- `docs/sprints/plan-sprints-candidato.md`: resultado candidato de Sprint 5 y pendientes de validación.
- `docs/casos-de-uso/flujo-candidato-practica.md`: secuencia de término/expediente, seguimiento y encuesta.
- `.ai/state/topics/resumen.md` y `.ai/state/topics/pendientes.md`: continuidad actualizada.

## Decisiones confirmadas
- La empresa entrega al alumno la carta de término, firmada por su responsable. Incluye alumno, empresa, periodo de práctica, total de 240 horas, actividades y fecha. No se crea contador de horas.
- El alumno carga PDF/JPG/PNG de hasta 20 MB. Coordinación revisa, acepta o devuelve con comentario obligatorio. El alumno reemplaza y reenvía con intentos ilimitados; solo se guarda la versión vigente.
- Expediente final: carta aceptada, evaluaciones terminales separadas de Tutor y Supervisor y reporte final aprobado; sin informes intermedios. Alumno y Coordinación consultan el expediente completo; Tutor y Supervisor únicamente su propia evaluación. El expediente cierra cuando están los cuatro elementos y se conserva mientras exista el expediente.
- Seguimiento: una fila por práctica con solicitud, aceptación, plan, evaluaciones, reporte y cierre, cada una con estado y pendientes. Filtros por estado y búsqueda por alumno/empresa. Alertas solo dentro de la plataforma para Coordinación y el actor responsable; la fila abre la práctica, no ejecuta aprobaciones/devoluciones.
- Encuesta separada de evaluaciones terminales, sin bloquear cierre y sin plazo fijo. Coordinación configura preguntas institucionales con escala y comentario opcional; cada solicitud fija la versión vigente. El supervisor directo responde dentro de plataforma con acceso autenticado. Estados solicitada/respondida/cancelada por Coordinación, sin vencimiento automático; Coordinación cancela solicitudes pendientes. Una respuesta final, única e inmutable. Respuestas ligadas a práctica/empresa, solo visibles para Coordinación y conservadas mientras exista el expediente.

## Validación
- `python3 tools/continuum doctor`: 0 problemas críticos; 1 advertencia por costo estimado del paquete de arranque.
- `git -c core.whitespace=cr-at-eol diff --check`: sin errores.
- No se ejecutaron pruebas de aplicación: no se modificó código.

## Riesgos / pendientes
- Los criterios son base del prototipo, no sustituyen validación institucional. Confirmar permisos y resguardo con actores y precisar comportamiento técnico de alertas al refinar implementación.
- No se modificaron ni copiaron los formatos fuente de `docs/formatos/`, que pueden contener datos personales. No trasladar los requisitos de 800 horas de estancias profesionales.
- No se implementaron historias de Sprint 5 ni se comprometió fecha/duración/estimación.

## Siguiente paso
Crear tareas Continuum separadas por historia antes de implementar Sprint 5; partir de `develop`, validar dependencias y dimensionamiento, y mantener la separación entre refinamiento candidato y aprobación institucional.
