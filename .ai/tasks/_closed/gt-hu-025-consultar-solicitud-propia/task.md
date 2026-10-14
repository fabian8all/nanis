# Tarea: gt-hu-025-consultar-solicitud-propia

**Creada:** 2026-10-14 · **Tamaño:** medium · **Owner:** backend · **Rol:** Backend (`software/backend`)

## Objetivo
Permitir que un alumno consulte su propia solicitud y mantener la separación
de acceso entre alumnos, Coordinación, Supervisión y Tutoría.

## Incluido en el alcance
- El alumno autenticado puede consultar su propia solicitud sin recibir 403.
- Impedir que consulte la solicitud de otra persona manipulando la URL o el ID.
- Conservar el acceso de revisión que corresponde a Coordinación.
- Probar accesos permitidos y denegados para los roles relevantes.

## Dependencias
- Autorización y consulta de solicitudes existentes: GT-HU-001 y GT-HU-002.

## Explícitamente fuera de alcance
- Ampliar el acceso de Supervisión o Tutoría a datos de solicitudes.
- Cambiar el flujo de creación o revisión de solicitudes.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/routes/web.php`
- `project-app/app/Livewire/Student/PracticeRequests/Show.php`
- `project-app/tests/Feature/StudentPracticeRequestTest.php`
- `project-app/tests/Feature/CoordinatorPracticeRequestReviewTest.php`
- `.ai/tasks/gt-hu-025-consultar-solicitud-propia/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-025)
- `project-app/routes/web.php`
- `project-app/app/Livewire/Student/PracticeRequests/Show.php`
- `project-app/tests/Feature/StudentPracticeRequestTest.php`
- `project-app/tests/Feature/CoordinatorPracticeRequestReviewTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
