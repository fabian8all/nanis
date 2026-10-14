# Notas de trabajo: gt-hu-033-acciones-presentacion-aceptacion

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- Ambas acciones deben apuntar al recurso de la práctica del supervisor
  asignado, nunca a una carta de otra empresa.
- La persona solicitante eligió botones separados para estas dos acciones.

## Evidencia recopilada
- `PracticeTrackingSummary::acceptanceStage` actualmente ofrece una sola ruta
  de acción para Supervisor: `supervisor.practice-letters.index`.
- La página de cartas ya enlaza la carta de presentación y la carga de
  aceptación, y limita cartas por `company_id` del Supervisor autenticado.
- `routes/web.php` define `supervisor.practice-letters.show` y
  `supervisor.practice-acceptances.upload`.

## Callejones sin salida (para no repetirlos)
- No duplicar ni relajar validaciones, almacenamiento privado o revisión de
  Coordinación que ya protegen la carga.
