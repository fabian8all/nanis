# Handoff: gt-hu-006-implementacion-evaluacion-supervisor

## Objetivo
Implementar evaluación terminal de supervisor (GT-HU-006) sobre una práctica con aceptación y plan aprobados.

## Criterios
- Evaluador: supervisor que cargó la aceptación empresarial aprobada.
- Habilitada al aprobar HU-005, sin depender de HU-016 ni de cumplir 240 horas.
- Fija versión publicada de rúbrica Supervisor y aceptación aprobada al crearla.
- Una evaluación por práctica; borrador editable hasta un envío final inmutable.
- Todos los criterios obligatorios; puntaje ponderado normalizado 0–100 con detalle.
- Consulta por alumno propietario, supervisor evaluador y Coordinación.

## Hallazgos técnicos
- HU-016 utiliza `practice_evaluations` con unicidad por práctica; HU-006 requiere tablas separadas para no prohibir ambas evaluaciones.
- Reutilizar `RubricScoreCalculator` y el patrón de snapshots de HU-016.
- La rúbrica Supervisor institucional sigue incompleta/no publicada. No agregar criterios ficticios para HU-006; Coordinación debe publicarla por HU-017.

## Estado
Implementación y despliegue completados en `sprint/4-evaluaciones-reporte`, commit `77d7a55`. Pruebas dirigidas en SQLite: 4 pruebas/33 aserciones; Pint PASS en 11 archivos PHP. Migración `2026_10_11_000014` aplicada a MySQL de desarrollo y `http://nanis.net` responde HTTP 200. Durante una prueba inicial apuntada por error a MySQL se crearon dos tablas parciales vacías; se confirmó que no tenían filas, se eliminaron solo esas tablas temporales y luego la migración completa pasó. Se preservó el `.env`/APP_KEY runtime al recrear el contenedor. La rúbrica Supervisor sigue sin publicar, así que la evaluación queda correctamente bloqueada hasta que Coordinación la complete. Tarea Continuum cerrada y archivada.
