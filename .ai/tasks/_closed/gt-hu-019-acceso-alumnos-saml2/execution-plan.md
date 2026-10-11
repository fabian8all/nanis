# Plan de ejecución: gt-hu-019-acceso-alumnos-saml2

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Elegir el toolkit compatible y documentar el claim configurable con el valor provisional `uCorreo`; su nombre real debe confirmarse con el administrador del IDP.
- [x] Paso 2 — Implementar login SAML2 y alta local automática de alumnos con simulación solo en desarrollo/pruebas.
- [x] Paso 3 — Probar los flujos del prototipo, validar en Docker, documentar, actualizar handoff, archivar y comprometer; reanudar HU-014.

## Estado actual
Tarea archivada; su commit se registra en la rama de Sprint 1. `onelogin/php-saml`
4.3.2 quedó integrado para Laravel 13/PHP 8.4; el
nombre del claim queda configurable y usa provisionalmente `uCorreo`. Se
implementaron la transición a rol único, el servicio SAML, el aprovisionador,
ACS con request ID correlacionado a un RelayState opaco de un solo uso guardado
en cache (sin depender de la cookie de sesión cross-site), metadata SP,
configuración y simulador limitado por entorno. La simulación replica
`uCorreo`, `uNombre`, `sn`, `givenName` y `displayName` mediante
`SAML_SIMULATOR_*`; los valores del `.env.example` son sintéticos. La suite
completa pasa: 23 pruebas/101 aserciones; Pint correcto; login local muestra el
simulador y metadata responde HTTP 200. Datos y claim reales del IDP se
incorporarán posteriormente y no bloquean el prototipo simulado.
