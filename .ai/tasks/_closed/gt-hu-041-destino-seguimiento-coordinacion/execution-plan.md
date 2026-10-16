# Plan de ejecución: gt-hu-041-destino-seguimiento-coordinacion

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Verificar los destinos actuales local/SAML y los controles de
  autenticación y autorización.
- [x] Paso 2 — Cambiar solo el destino SAML de Coordinación y añadir prueba de
  regresión para los roles afectados.
- [x] Paso 3 — Ejecutar pruebas dirigidas, dejar handoff y obtener aprobación
  de cierre antes de crear el commit.

## Estado actual
La autenticación local de Coordinación ya redirige a Seguimiento. El controlador
SAML ahora dirige a Coordinación a Seguimiento cuando no hay destino previo.
Pint pasó en los dos archivos PHP y la regresión dirigida pasó con 25 tests y
137 assertions. La persona solicitante aprobó el cierre y la tarea quedó
archivada; el cambio se compromete en la rama del Sprint 1.
