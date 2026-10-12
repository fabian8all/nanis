# Notas de trabajo: gt-hu-010-seguimiento-practicas

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- Derivar la fase de cierre de HU-012 tras implementarla; por ahora el tablero calcula el cierre pendiente sin declarar completo.
- Los recordatorios in-app son un resumen dinámico de las acciones pendientes, no notificaciones persistidas ni correo; decisión de implementación alineada con el criterio de HU-010.

## Evidencia recopilada
- Estados existentes: solicitud `pending/returned/approved/completed`; aceptación `pending_review/returned/accepted`; plan `pending_supervisor_review/pending_coordinator_review/returned_by_supervisor/returned_by_coordinator/approved`; reporte `draft/pending_tutor_review/returned/approved`; evaluaciones `draft/submitted`.
- Evaluación Supervisor asignada al supervisor de la carta de aceptación aprobada; evaluación Tutor ligada a asignación de tutor y reporte aprobado.

## Callejones sin salida (para no repetirlos)
- No tratar el estado `completed` en solicitud como equivalente a expediente completo sin HU-012.
- No inferir hitos por horas.

## Validación
- `PracticeTrackingTest`: 4 pruebas y 27 aserciones aprobadas, incluyendo visibilidad del pendiente del alumno y de Supervisor, aislamiento por rol y filtros/búsqueda de Coordinación.
- Pint `--test`: aprobado para `PracticeTrackingSummary.php`, `PracticeTracking/Index.php` y `PracticeTrackingTest.php`.
- No se desplegó la aplicación ni se aplicó a MySQL la migración pendiente de HU-009.
