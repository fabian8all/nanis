# Plan de ejecución: gt-hu-052-verificar-rubricas-coordinacion

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Revisar la ruta, navegación y pruebas de autorización existentes.
- [x] Paso 2 — Ejecutar las pruebas de Coordinación y registrar la evidencia.
- [x] Paso 3 — Completar handoff y obtener aprobación de cierre antes del
  commit de la tarea de verificación.

## Estado actual
La ruta `coordinacion.rubrics.manage`, el enlace de navegación y las pruebas de
acceso por rol están presentes. Las suites de navegación y gestión pasaron con
11 tests y 52 assertions. No se detectaron defectos; la persona solicitante
aprobó el cierre y la tarea quedó archivada. Crear el commit de verificación.
