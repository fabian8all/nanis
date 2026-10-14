# Handoff: gt-hu-023-destino-alumno-estado-practica

**Fecha:** 2026-10-14 · **Rol:** Backend (`software/backend`)

## Objetivo
Dirigir al alumno a su siguiente área según el estado vigente de su solicitud,
tanto tras autenticación local como institucional.

## Archivos revisados
- `.ai/tasks/gt-hu-023-destino-alumno-estado-practica/task.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-023 y preguntas abiertas)
- `docs/v1.1/sprints/plan-sprints-candidato.md`
- `project-app/app/Enums/PracticeRequestStatus.php`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/app/Http/Controllers/Auth/SamlSessionController.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`
- `project-app/tests/Feature/SamlStudentAuthenticationTest.php`

## Archivos modificados
- `project-app/app/Services/StudentPracticeDestination.php`
- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/app/Http/Controllers/Auth/SamlSessionController.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`
- `project-app/tests/Feature/SamlStudentAuthenticationTest.php`
- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/sprints/plan-sprints-candidato.md`

## Decisión(es) tomada(s)
- Sin solicitud, dirigir al catálogo; solicitud devuelta, al formulario de
  corrección; estados pendiente, aprobada y completada, a Seguimiento.
- Obtener el estado más reciente desde la relación de solicitudes del alumno
  autenticado, no desde `url.intended` ni otra información de sesión.
- Limpiar `url.intended` al autenticar al alumno; conservar el comportamiento
  de redirección por URL prevista para otros roles SAML.
- No crear estados cancelado o rechazado, que no existen en
  `PracticeRequestStatus`.

## Suposiciones vigentes
- Si se agregan estados nuevos de solicitud en el futuro, se deberá definir su
  destino de autenticación en una historia posterior.

## Validación
- Ejecutada: PHPUnit en el contenedor para
  `CoordinatorAuthenticationTest.php` y `SamlStudentAuthenticationTest.php`:
  24 tests y 130 aserciones pasaron sin advertencias.
- Ejecutada: Laravel Pint `--test` para los cinco archivos PHP modificados.
- Ejecutada: `git diff --check` con `core.whitespace=cr-at-eol`.
- Pendiente: ninguna para esta tarea.

## Riesgos / dudas abiertas
- Los estados cancelado y rechazado quedan sin destino hasta que se agreguen
  formalmente al modelo.

## Siguiente paso recomendado
Cerrar la tarea, integrar el Sprint 1 validado a `develop` y dejar HU-024 como
la siguiente historia planificada, creando su tarea al iniciar Sprint 2.
