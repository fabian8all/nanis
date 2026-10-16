# Handoff: GT-HU-041 — Destino de Coordinación

## Resultado

El controlador SAML ahora usa Seguimiento como destino por defecto de
Coordinación, en lugar de `home`. La autenticación local ya redirigía a
Seguimiento y no se modificó. Los otros roles conservan el destino previo de
este flujo para que GT-HU-042 pueda cambiar el Supervisor de forma separada.

## Validación

- `CoordinatorSamlAuthenticationTest`, `SamlStudentAuthenticationTest` y
  `CoordinatorAuthenticationTest`: 25 tests, 137 assertions, correctos.
- Pint `--test` pasó para `SamlSessionController.php` y
  `CoordinatorSamlAuthenticationTest.php`.
- La aplicación se reconstruyó para incluir los cambios del checkout y las
  pruebas se ejecutaron en el contenedor actualizado.

## Estado de cierre

La persona solicitante aprobó el cierre y la tarea quedó archivada. El cambio
se compromete en `sprint/v1.2-1-acceso-navegacion-rol`; no se integrará el
sprint a `develop` hasta completar y validar sus cuatro historias.
