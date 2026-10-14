# Handoff: gt-hu-027-destino-coordinacion-seguimiento

**Fecha:** 2026-10-14 · **Rol:** Backend (`software/backend`)

## Objetivo
Enviar a Coordinación a Seguimiento después de iniciar sesión, sin cambiar
los controles de acceso ni los destinos de los demás roles.

## Archivos revisados
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-027)
- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/routes/web.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`

## Archivos modificados
- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`

## Decisión(es) tomada(s)
- Cambiar únicamente el destino de Coordinación a
  `coordinacion.practice-tracking.index`.
- Mantener la autenticación y autorización de la ruta; cubrir acceso directo
  sin autenticar y denegación para un alumno.
- Añadir cobertura del destino existente de Tutoría junto a las pruebas
  existentes de Alumno y Supervisión.
- Deshabilitar el middleware de throttling en esta clase de pruebas para que
  cada prueba de destino de login sea independiente; el middleware productivo
  no cambió.

## Suposiciones vigentes
- El nombre de ruta `coordinacion.practice-tracking.index` es la vista aprobada
  de Seguimiento para Coordinación.

## Validación
- Ejecutada: PHPUnit en el contenedor para
  `CoordinatorAuthenticationTest.php`: 8 tests y 42 aserciones pasaron sin
  advertencias.
- Pendiente: ninguna para esta tarea.

## Riesgos / dudas abiertas
- Ninguno identificado.

## Siguiente paso recomendado
Continuar con GT-HU-034. GT-HU-023 sigue bloqueada hasta acordar los destinos
de los estados que permanecen abiertos.
