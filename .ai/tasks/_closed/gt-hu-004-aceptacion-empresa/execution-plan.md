# Plan de ejecución: gt-hu-004-aceptacion-empresa

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Modelar vínculo supervisor/empresa, invitación de un solo uso, verificación de correo y acceso local por rol.
- [x] Paso 2 — Implementar consulta empresarial de cartas, carga privada versionada y revisión de Coordinación.
- [x] Paso 3 — Probar permisos, flujo de invitación, archivos e historial; actualizar documentación y validar Sprint 3.

## Estado actual
Implementación y validación terminadas en `sprint/3-aceptacion-empresa`. Suite completa: 58 pruebas/365 aserciones; Pint pasa para los PHP de HU-004. Migraciones aplicadas y entorno Docker reconstruido.
