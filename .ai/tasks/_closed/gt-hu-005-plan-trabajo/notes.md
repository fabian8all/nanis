# Notas de trabajo: gt-hu-005-plan-trabajo

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

- El alumno registra actividades con descripción, fechas de inicio y término,
  responsable de la empresa como texto libre y resultado esperado.
- No se establece fecha límite. Solo se valida que el término de cada
  actividad no sea anterior al inicio; las fechas no se restringen al periodo
  oficial de la práctica.
- El plan se habilita después de que Coordinación aprueba la carta de
  aceptación.
- Flujo de revisión: supervisor que cargó la aceptación → Coordinación. Cada
  revisor puede aprobar o devolver; para devolver debe dejar comentario.
- El alumno solo modifica el plan devuelto. Cada reenvío vuelve al supervisor
  y crea una versión, conservando actividades, estados y comentarios previos.
- Solo el alumno propietario, el supervisor que aceptó la práctica y
  Coordinación consultan las versiones.

## Evidencia recopilada

- GT-HU-005 aparece en `docs/backlog/candidatos-iniciales.md` con el formato,
  revisión, fecha límite y permisos todavía por definir.
- GT-HU-005 está en Sprint 3, después de la aceptación de empresa
  (`docs/sprints/plan-sprints-candidato.md`).
- GT-HU-004 quedó comprometida en `00db4dc` sobre
  `sprint/3-aceptacion-empresa`.

## Arquitectura prevista

- Un plan por solicitud aprobada, con versiones independientes y actividades
  guardadas por versión.
- Cada versión avanza por revisión del supervisor, revisión de Coordinación,
  devolución por una de esas etapas o aprobación final.
- La cuenta supervisora responsable se obtiene de la entrega de aceptación
  aprobada, no solo del `company_id`, porque puede haber varias cuentas por
  empresa.

## Callejones sin salida (para no repetirlos)
- Los formatos institucionales disponibles son de estancias profesionales
  (800 horas), y GT-HU-005 es el plan del prototipo de prácticas; no reutilizar
  ese formato sin validación explícita.
