# Tarea: gt-hu-028-navegacion-coordinacion

**Creada:** 2026-10-14 · **Tamaño:** small · **Owner:** frontend · **Rol:** Frontend (`software/frontend`)

## Objetivo
Agrupar en la navegación de Coordinación el catálogo y los documentos
existentes, y dejar a mano el acceso operativo al módulo de Rúbricas sin
alterar las rutas ni sus permisos.

## Incluido en el alcance
- Mantener el acceso superior al catálogo de empresas.
- Agrupar en un menú `Documentos` las Solicitudes, Aceptaciones, Cartas de
  término, Planes, Reportes, Evaluaciones de tutor, Evaluaciones de supervisor
  y Encuestas.
- Conservar el enlace `Rúbricas` hacia el módulo existente.
- Verificar por pruebas los destinos y que la navegación no aparece para
  otros roles.

## Explícitamente fuera de alcance
- Cambiar rutas, lógica de negocio, permisos o contenido de los módulos.
- Reorganizar la navegación de Alumno, Supervisor o Tutor.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/resources/views/layouts/app.blade.php`
- `project-app/tests/Feature/CoordinatorNavigationTest.php`
- `project-app/tests/Feature/CoordinatorRubricManagementTest.php`
- `.ai/tasks/gt-hu-028-navegacion-coordinacion/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-028)
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/routes/web.php`
- `project-app/tests/Feature/CoordinatorRubricManagementTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
