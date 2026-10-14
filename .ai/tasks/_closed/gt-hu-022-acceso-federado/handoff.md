# Handoff: gt-hu-022-acceso-federado

**Fecha:** 2026-10-14 · **Rol:** Frontend (`software/frontend`)

## Objetivo
Actualizar el rótulo y la ubicación del acceso federado en la pantalla de
inicio de sesión sin cambiar sus destinos SAML.

## Archivos revisados
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-022)
- `project-app/resources/views/auth/login.blade.php`
- `project-app/tests/Feature/SamlStudentAuthenticationTest.php`

## Archivos modificados
- `project-app/resources/views/auth/login.blade.php`
- `project-app/tests/Feature/SamlStudentAuthenticationTest.php`

## Decisión(es) tomada(s)
- Usar **Acceso federado UCOL** para el simulador local y el flujo SAML
  configurado, y mostrar el enlace después del botón **Iniciar sesión**.
- Mantener el destino configurado de cada modo: simulador o autenticación
  institucional.

## Suposiciones vigentes
- Ninguna.

## Validación
- Ejecutada: `./vendor/bin/phpunit --display-warnings tests/Feature/SamlStudentAuthenticationTest.php`
  dentro del contenedor: 12 tests y 60 aserciones pasaron sin advertencias.
- Ejecutada: `git diff --check` con `core.whitespace=cr-at-eol`.
- Pendiente: no hay validación funcional pendiente para esta HU.

## Riesgos / dudas abiertas
- La primera ejecución mediante `php artisan test` tras reconstruir la imagen
  mostró una advertencia en una prueba existente; no se reprodujo al ejecutar
  PHPUnit directamente con advertencias visibles.

## Siguiente paso recomendado
Continuar en serie con otra HU no bloqueada de Sprint 1. GT-HU-023 espera una
decisión de producto sobre destinos para ciertos estados.
