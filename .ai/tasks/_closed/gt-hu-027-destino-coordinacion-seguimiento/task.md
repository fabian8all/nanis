# Tarea: gt-hu-027-destino-coordinacion-seguimiento

**Creada:** 2026-10-14 · **Tamaño:** small · **Owner:** backend · **Rol:** Backend (`software/backend`)

## Objetivo
Al autenticarse, llevar a Coordinación directamente a Seguimiento y conservar
la autenticación obligatoria y los destinos de los demás roles.

## Incluido en el alcance
- Después de una autenticación válida, Coordinación llega a Seguimiento.
- Una persona no autenticada debe iniciar sesión antes de ver Seguimiento.
- Verificar que los destinos de Supervisor, Tutor y Alumno no cambien.

## Dependencias
- Ruta y funcionalidad de Seguimiento existentes: GT-HU-010.

## Explícitamente fuera de alcance
- Cambiar rutas, permisos o destinos de los demás roles.
- Reorganizar la navegación de Coordinación (GT-HU-028).

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`
- `.ai/tasks/gt-hu-027-destino-coordinacion-seguimiento/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-027)
- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/routes/web.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
