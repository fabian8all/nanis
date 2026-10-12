# Handoff

**Fecha:** 2026-10-12 · **Rol:** software/backend · **Rama:** `sprint/5-cierre-expediente-encuesta`

## Objetivo de la sesión
Continuar Sprint 5 hasta completarlo, cerrando cada historia por separado con tarea Continuum, validación y commit en la rama del sprint. HU-010 terminó; HU-012 quedó iniciada cuando el usuario pidió pausar.

## Estado
- HU-010 implementada y comprometida como `e40197e`; las pruebas dirigidas dieron 4 pruebas/27 aserciones y Pint pasó en 3 PHP.
- HU-012 activa y reclamada por Copilot: `.ai/tasks/gt-hu-012-expediente-practicas/`. Su código de aplicación aún no se ha modificado. El plan está en el paso 1 (investigar permisos/modelos).
- HU-013 sigue sin tarea de implementación. La escala elegida por el usuario para la encuesta es de 1 a 5; falta incorporarla formalmente al alcance/backlog al iniciar HU-013.
- Migración HU-009 `2026_10_12_000015` todavía no se ha aplicado al MySQL local. No desplegar ni recrear la app al retomar sin preservar `.env` y APP_KEY runtime.

## Investigación de HU-012 ya realizada
- `PracticeRequest` relaciona `terminationLetter`, `practiceReport`, `practiceEvaluation` y `practiceSupervisorEvaluation`.
- `PracticeTerminationLetterFileController` autoriza descarga solo a Coordinación y alumno propietario.
- `PracticeReportAttachmentController` autoriza Coordinación, alumno propietario y Tutor asignado. No cambiar este permiso existente; la vista consolidada se limita a Coordinación y alumno propietario.
- Las evaluaciones tienen rutas de resultado separadas por rol. El reporte aprobado está en `practiceReport.approvedVersion` y sus anexos en `attachments`.
- Criterio de completitud acordado: carta de término aceptada + reporte final aprobado + evaluaciones terminales de Tutor y Supervisor enviadas. HU-013 no bloquea el cierre.

## Próximo paso
Retomar leyendo `.ai/HANDOFF.md`, `.ai/state/estado-dev.md` y `.ai/tasks/gt-hu-012-expediente-practicas/`. Completar el análisis puntual de estados/rutas y luego implementar la vista consolidada, accesible solo por Coordinación y alumno propietario, con documentos/resultados, elementos pendientes y estado completo/incompleto. Enlazar desde seguimiento y la vista de solicitud del alumno. Probar estado incompleto/completo, propiedad del alumno, acceso de Coordinación y denegación a Tutor/Supervisor; actualizar handoff, cerrar la tarea y comprometerla.

Después iniciar HU-013 como tarea independiente, registrar escala 1–5 y terminar Sprint 5 antes de integrarlo en `develop`.

## Cambios ajenos que deben preservarse
No incluir ni revertir los cambios preexistentes en `.continuum/tools/continuum`, `.githooks/pre-commit`, `tools/continuum`, `docs/formatos/` ni archivos `Zone.Identifier`. Las fuentes de `docs/formatos/` pueden contener datos personales.
