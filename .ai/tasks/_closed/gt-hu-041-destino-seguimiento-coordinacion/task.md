# Tarea: gt-hu-041-destino-seguimiento-coordinacion

**Creada:** 2026-10-16 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** Backend (`software/backend`)

## Objetivo
Después de la autenticación SAML, dirigir a Coordinación directamente a
Seguimiento en lugar de la página intermedia `home`. Mantener el destino local
actual y preservar los destinos y permisos de los demás roles.

## Incluido en el alcance
- Confirmar el destino actual de Coordinación para autenticación local y SAML.
- Redirigir a Seguimiento después del acceso SAML de Coordinación cuando no
  exista un destino protegido previo válido.
- Añadir regresión para Coordinación y comprobar que los destinos de Alumno,
  Supervisor y Tutor no cambian.

## Explícitamente fuera de alcance
- Cambiar el destino local de Coordinación, que ya está cubierto por pruebas.
- Cambiar el destino del Supervisor, que corresponde a GT-HU-042.
- Cambiar el flujo de autenticación o las políticas de autorización.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Http/Controllers/Auth/SamlSessionController.php`
- `project-app/tests/Feature/CoordinatorSamlAuthenticationTest.php`
- `.ai/tasks/gt-hu-041-destino-seguimiento-coordinacion/task.md`
- `.ai/tasks/gt-hu-041-destino-seguimiento-coordinacion/execution-plan.md`
- `.ai/tasks/gt-hu-041-destino-seguimiento-coordinacion/notes.md`
- `.ai/tasks/gt-hu-041-destino-seguimiento-coordinacion/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.2/backlog/backlog-candidato.md` (GT-HU-041)
- `project-app/app/Http/Controllers/Auth/SamlSessionController.php`
- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`
- `project-app/tests/Feature/SamlStudentAuthenticationTest.php`

## Criterios de aceptación
- Coordinación que completa el acceso SAML sin destino previo llega a
  Seguimiento, no a `home`.
- El acceso local de Coordinación conserva su redirección actual a
  Seguimiento.
- El comportamiento de Alumno, Supervisor y Tutor no cambia como efecto
  colateral; las rutas siguen protegidas por autenticación/autorización.
- Las pruebas dirigidas de autenticación pasan.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
