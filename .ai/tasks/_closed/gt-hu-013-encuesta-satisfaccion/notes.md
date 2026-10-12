# Notas de trabajo: gt-hu-013-encuesta-satisfaccion

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
-

## Evidencia recopilada
-

## Callejones sin salida (para no repetirlos)
-
# Notas de trabajo: gt-hu-013-encuesta-satisfaccion

## Decisiones operativas
- Cuestionario global con preguntas de texto administradas por Coordinación;
  cada respuesta elige un entero de 1 a 5.
- Cuestionario versionado e inmutable al publicar; cada solicitud fija una
  versión para preservar las preguntas respondidas.
- El destino es el supervisor autenticado de la aceptación empresarial vigente.
- Una solicitud por práctica; cancelar es terminal. No hay vencimiento ni
  recordatorios ni segunda solicitud después de cancelar.
- La vista de supervisor muestra solo solicitudes pendientes y nunca permite
  consultar respuestas enviadas; Coordinación es el único rol lector.

## Evidencia
- Los criterios confirmados están en `docs/backlog/candidatos-iniciales.md` y
  `.ai/tasks/_closed/gt-sprint-5-refinamiento-cierre-expediente-encuesta/handoff.md`.
- HU-012 ya dispone de una ruta independiente de cierre; la encuesta no forma
  parte de sus cuatro requisitos.
- Cobertura agregada: 100 pruebas/748 aserciones aprobadas; HU-013 dirigida:
  5 pruebas/62 aserciones. Pint pasa en los PHP modificados.
