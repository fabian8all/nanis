# Tarea: gt-hu-042-destino-seguimiento-supervisor

**Creada:** 2026-10-16 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** Backend (`software/backend`)

## Objetivo
Dirigir al Supervisor a Seguimiento al iniciar sesión, en vez de Cartas
pendientes de aceptación. Conservar el alcance de datos por empresa y práctica
asignadas en ambos métodos de autenticación.

## Incluido en el alcance
- Cambiar el destino local del Supervisor a `supervisor.practice-tracking.index`.
- Cambiar el destino SAML del Supervisor a Seguimiento.
- Añadir pruebas de regresión para ambos métodos y preservar las rutas de los
  demás roles.

## Explícitamente fuera de alcance
- Cambiar navegación, estados o controles de seguimiento fuera de la redirección.
- Ampliar las prácticas o empresas visibles al Supervisor.
- Cambiar el acceso de Coordinación, Alumno o Tutor.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/app/Http/Controllers/Auth/SamlSessionController.php`
- `project-app/app/Http/Middleware/EnsureSupervisor.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`
- `project-app/tests/Feature/PracticeTrackingTest.php`
- `project-app/tests/Feature/SamlRoleAuthenticationTest.php`
- `.ai/tasks/gt-hu-042-destino-seguimiento-supervisor/task.md`
- `.ai/tasks/gt-hu-042-destino-seguimiento-supervisor/execution-plan.md`
- `.ai/tasks/gt-hu-042-destino-seguimiento-supervisor/notes.md`
- `.ai/tasks/gt-hu-042-destino-seguimiento-supervisor/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.2/backlog/backlog-candidato.md` (GT-HU-042)
- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/app/Http/Controllers/Auth/SamlSessionController.php`
- `project-app/routes/web.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`
- `project-app/tests/Feature/SamlStudentAuthenticationTest.php`

## Criterios de aceptación
- Después del inicio local o SAML, Supervisor llega a Seguimiento.
- Un Supervisor no verificado o sin empresa asignada conserva la denegación
  actual.
- Las prácticas accesibles siguen limitadas a las asignadas al Supervisor.
- Los destinos de Coordinación, Alumno y Tutor no cambian.
- Las pruebas dirigidas de autenticación y autorización pasan.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
