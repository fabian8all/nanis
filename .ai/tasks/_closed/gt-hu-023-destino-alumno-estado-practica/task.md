# Tarea: gt-hu-023-destino-alumno-estado-practica

**Creada:** 2026-10-14 · **Tamaño:** medium · **Owner:** backend · **Rol:** Backend (`software/backend`)

## Objetivo
Dirigir al alumno al catálogo o a Seguimiento según el estado vigente de su
solicitud o práctica, sin depender de datos obsoletos de sesión ni exponer
registros de otras personas.

**Refinamiento confirmado:** sin solicitud se dirige al catálogo; una solicitud
devuelta se dirige al formulario de corrección; los estados pendiente, aprobada
y completada se dirigen a Seguimiento. `PracticeRequestStatus` no incluye
estados cancelado ni rechazado; no se agregarán en esta historia.

## Incluido en el alcance
- Alumno sin solicitud: dirigirlo al catálogo de empresas con convenio.
- Solicitud pendiente, aprobada o completada: dirigirlo a Seguimiento.
- Solicitud devuelta: dirigirlo al formulario de corrección.
- Resolver el destino consultando el estado persistido vigente.
- Preservar el aislamiento de solicitudes de otros alumnos.
- Cubrir con pruebas los estados acordados en los flujos de autenticación
  local y SAML aplicables.

## Dependencias
- Capacidades existentes de autenticación y flujo/estado: GT-HU-019,
  GT-HU-001 y GT-HU-010.
- La persona solicitante confirmó el destino para solicitud devuelta y
  práctica completada; los estados cancelado y rechazado no existen actualmente.

## Explícitamente fuera de alcance
- Cambiar el proceso de autenticación SAML o local.
- Cambiar las reglas de selección de empresa de GT-HU-024.
- Crear estados cancelado o rechazado; si se incorporan después, requerirán
  una decisión de destino propia.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/app/Http/Controllers/Auth/SamlSessionController.php`
- `project-app/app/Services/StudentPracticeDestination.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`
- `project-app/tests/Feature/SamlStudentAuthenticationTest.php`
- `project-app/tests/Feature/StudentPracticeRequestTest.php`
- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/sprints/plan-sprints-candidato.md`
- `.ai/tasks/gt-hu-023-destino-alumno-estado-practica/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-023 y pregunta abierta)
- `docs/v1.1/sprints/plan-sprints-candidato.md` (refinamiento de Sprint 1)
- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/app/Http/Controllers/Auth/SamlSessionController.php`
- `project-app/app/Enums/PracticeRequestStatus.php`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/routes/web.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
