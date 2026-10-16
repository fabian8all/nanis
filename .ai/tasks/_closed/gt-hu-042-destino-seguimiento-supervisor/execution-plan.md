# Plan de ejecución: gt-hu-042-destino-seguimiento-supervisor

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Inspeccionar destinos local/SAML, validación de Supervisor y
  alcance actual de Seguimiento.
- [x] Paso 2 — Redirigir ambos métodos a Seguimiento y ampliar las regresiones
  para destino, estado de cuenta y aislamiento.
- [x] Paso 3 — Ejecutar pruebas dirigidas, dejar handoff y obtener aprobación
  de cierre antes de crear el commit.

## Estado actual
Los accesos local y SAML dirigen al Supervisor verificado y asignado a empresa
a Seguimiento. `EnsureSupervisor` exige rol, correo verificado y empresa en
todas las rutas Supervisor. Las regresiones dirigidas pasaron (38 tests, 223
assertions) y Pint pasó en seis archivos. La persona solicitante aprobó el
cierre y la tarea quedó archivada; el cambio se compromete en la rama del
Sprint 1.
