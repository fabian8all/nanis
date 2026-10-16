# Handoff: GT-HU-052 — Verificar Rúbricas de Coordinación

## Resultado

La ruta `coordinacion.rubrics.manage` está registrada bajo autenticación y
autorización de Coordinación, y el layout ofrece el enlace Rúbricas. Las pruebas
confirman que Coordinación abre el módulo y que un estudiante no lo abre ni ve
el enlace. No se requirieron cambios de aplicación.

## Validación

- `CoordinatorNavigationTest` y `CoordinatorRubricManagementTest`: 11 tests,
  52 assertions, correctos.

La persona solicitante aprobó el cierre y la tarea quedó archivada. El commit
de verificación se crea en `sprint/v1.2-1-acceso-navegacion-rol`; no se
integrará el sprint a `develop` hasta completar y validar sus cuatro historias.
