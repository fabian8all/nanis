# Tarea: gt-hu-052-verificar-rubricas-coordinacion

**Creada:** 2026-10-16 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** QA / Control de calidad del entregable (`comun/qa`)

## Objetivo
Verificar que el enlace Rúbricas de Coordinación continúa llevando al módulo
autorizado sin error 404. La ruta y las pruebas ya existen en `develop`, así
que el alcance es de validación y no de desarrollo duplicado.

## Incluido en el alcance
- Comprobar que Coordinación obtiene respuesta satisfactoria al abrir
  `coordinacion.rubrics.manage`.
- Verificar el enlace de navegación y que un rol no autorizado sigue recibiendo
  denegación.
- Ejecutar las pruebas de navegación y gestión de rúbricas y registrar su
  resultado en el handoff de la tarea.

## Explícitamente fuera de alcance
- Cambiar el módulo de Rúbricas si las pruebas existentes continúan pasando.
- Ampliar permisos o rehacer la navegación de Coordinación.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/gt-hu-052-verificar-rubricas-coordinacion/task.md`
- `.ai/tasks/gt-hu-052-verificar-rubricas-coordinacion/execution-plan.md`
- `.ai/tasks/gt-hu-052-verificar-rubricas-coordinacion/notes.md`
- `.ai/tasks/gt-hu-052-verificar-rubricas-coordinacion/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.2/backlog/backlog-candidato.md` (GT-HU-052)
- `project-app/routes/web.php`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/tests/Feature/CoordinatorNavigationTest.php`
- `project-app/tests/Feature/CoordinatorRubricManagementTest.php`

## Criterios de aceptación
- La ruta abre para Coordinación y presenta el módulo de administración.
- La navegación ofrece el enlace válido a la ruta.
- Un estudiante continúa sin permiso para abrir el módulo.
- Las pruebas dirigidas pasan; si fallan, registrar el hallazgo sin ampliar el
  alcance para corregirlo automáticamente.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
