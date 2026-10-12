# Notas de trabajo: gt-hu-016-implementacion-evaluacion-tutor

## Decisiones confirmadas
- Se habilita al aprobar HU-007, aunque falten horas; no espera HU-006.
- Evalúa el tutor asignado que aprobó el reporte.
- Al iniciar, fija versión aprobada del reporte y versión publicada vigente de la rúbrica Tutor.
- Una por práctica; borrador editable hasta un único envío. Criterios obligatorios; luego inmutable.
- Total ponderado normalizado 0–100 con detalle; consulta por alumno propietario, tutor evaluador y Coordinación; retención indefinida.

## Evidencia recopilada
- `PracticeReport.approved_version_id` identifica la versión aprobada; `PracticeReportVersion.reviewed_by` identifica al aprobador.
- `PracticeRequest.tutor_id` es la asignación establecida por HU-007.
- HU-017 implementa versiones de rúbrica publicadas e inmutables y `RubricScoreCalculator`.
- El seeder deja la rúbrica Tutor como borrador vacío, sin criterios/escala. No deben inventarse o publicarse criterios en esta historia.
- La migración siguiente a la de reportes es `2026_10_11_000013`.

## Pendiente de diseño/implementación
- Ninguno de implementación. Coordinación debe publicar la rúbrica Tutor institucional antes de iniciar la evaluación demo; no se inventaron criterios.

## Resultado
- Evaluación única por práctica con capturas de tutor, versión aprobada del reporte y versión publicada Tutor fijadas al inicio; snapshots de criterios/escala/peso y respuestas detalladas.
- Borrador editable con validación de niveles, envío único, cálculo normalizado 0–100 mediante `RubricScoreCalculator` y vistas autorizadas por rol.
- `DemoDataSeeder` crea cuentas sintéticas en local/testing únicamente, junto con una práctica aceptada con plan y reporte aprobados. Las cuentas aparecen una sola vez en su salida; no guardar la contraseña generada en Git.
- Validación dirigida: 4 pruebas/38 aserciones aprobadas; Pint pasó en los 14 PHP del write-set.
- Suite completa: 75 pruebas/513 aserciones; falla un test existente de formato de fechas SQLite en `CoordinatorPracticeRequestReviewTest`, ajeno a HU-016. No se modificó ese test.
- Desplegado en el contenedor activo sin recrearlo ni reemplazar `.env`/APP_KEY. Migración 000013 aplicada; `nanis.net/login` HTTP 200 y ruta de Tutor protegida redirige 302 al login.
