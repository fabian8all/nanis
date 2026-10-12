# Handoff: gt-hu-012-expediente-practicas

**Fecha:** 2026-10-12 · **Rol:** software/backend

## Objetivo
Entregar expediente consolidado y estado de cierre por práctica, accesible solo
para Coordinación y el alumno propietario.

## Archivos modificados
- `project-app/app/Livewire/PracticeRecords/Show.php` y su vista: autorización,
  requisitos de cierre, reporte aprobado, carta aceptada, anexos y resultados.
- `project-app/routes/web.php`: rutas de expediente separadas por perfil.
- `project-app/app/Services/PracticeTrackingSummary.php`: criterios de cierre
  reutilizables y pendientes detallados.
- Seguimiento y vista de solicitud estudiantil: enlaces al expediente.
- `project-app/tests/Feature/PracticeRecordTest.php` y
  `PracticeTrackingTest.php`: privacidad, cierre, enlaces y acceso por rol.
- `docs/backlog/candidatos-iniciales.md`: criterios de consulta y evidencia.

## Decisiones
- Completo únicamente con carta aceptada, reporte aprobado y ambas evaluaciones
  terminales enviadas.
- La vista no muestra versiones de borrador ni datos NSS/cuenta; anexos y carta
  continúan descargándose por sus controladores privados existentes.
- Tutor y Supervisor no acceden al expediente consolidado; sus accesos previos
  a reportes/anexos y resultados propios no se alteraron.

## Validación
- `php artisan test --filter='PracticeRecordTest|PracticeTrackingTest|StudentPracticeRequestTest|PracticeReportWorkflowTest|PracticeEvaluationWorkflowTest|PracticeSupervisorEvaluationWorkflowTest' --no-coverage`:
  23 pruebas y 212 aserciones aprobadas.
- Pint `--test` en los PHP cambiados: aprobado.
- `http://nanis.net/login` sigue respondiendo HTTP 200 tras reconstruir la app.

## Riesgos / pendientes
- La validación institucional de resguardo y permisos sigue pendiente.
- HU-013 es independiente del cierre y continúa en la cola del Sprint 5.

## Siguiente paso
Cerrar esta tarea con su commit en `sprint/5-cierre-expediente-encuesta` y
comenzar la tarea independiente de HU-013, conservando la escala confirmada
de 1 a 5.
