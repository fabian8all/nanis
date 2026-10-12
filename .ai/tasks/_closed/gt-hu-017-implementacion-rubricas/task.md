# Tarea: gt-hu-017-implementacion-rubricas

**Creada:** 2026-10-11 · **Tamaño:** large · **Owner:** backend · **Rol:** (sin asignar)

## Objetivo
Implementar la administración/versionado de rúbricas de evaluación independientes para supervisor y tutor (GT-HU-017), en la rama del Sprint 4, cumpliendo los criterios refinados antes de habilitar HU-006/HU-016.

## Incluido en el alcance
- Modelo relacional de rúbricas, versiones y criterios; borradores, publicación inmediata, inmutabilidad y conservación histórica.
- Interfaz de Coordinación para crear/editar/publicar versiones, gestionar criterios y asignar ponderaciones.
- Validación de ponderaciones al 100% y cálculo normalizado ponderado de 0 a 100.
- Autorización exclusiva a Coordinación, pruebas y documentación/handoff de la historia.
- Inicializar borradores incompletos mediante datos estructurales sin guardar los DOCX: criterios de supervisor legibles localmente, tutor sin criterios extraíbles. Coordinación completa/valida ambas rúbricas antes de publicar.

## Explícitamente fuera de alcance
- Captura de las evaluaciones HU-006 y HU-016.
- Incorporación de archivos fuente o datos personales al repositorio.
- Implementación del reporte final HU-007.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/gt-hu-017-implementacion-rubricas/task.md`
- `.ai/tasks/gt-hu-017-implementacion-rubricas/notes.md`
- `.ai/tasks/gt-hu-017-implementacion-rubricas/execution-plan.md`
- `.ai/tasks/gt-hu-017-implementacion-rubricas/handoff.md`
- `.ai/HANDOFF.md`
- `project-app/app/Enums/RubricType.php`
- `project-app/app/Models/Rubric.php`
- `project-app/app/Models/RubricVersion.php`
- `project-app/app/Models/RubricCriterion.php`
- `project-app/app/Services/RubricScoreCalculator.php`
- `project-app/app/Livewire/Coordinator/Rubrics/Manage.php`
- `project-app/database/migrations/2026_10_11_000011_create_rubrics_tables.php`
- `project-app/database/seeders/DatabaseSeeder.php`
- `project-app/resources/views/livewire/coordinator/rubrics/manage.blade.php`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/routes/web.php`
- `project-app/tests/Feature/CoordinatorRubricManagementTest.php`

La lista puede precisarse con rutas concretas antes de editar si la estructura existente indica otra superficie.

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/tasks/_closed/gt-hu-017-rubricas-evaluacion/handoff.md`
- `.ai/tasks/_closed/gt-hu-006-evaluacion-supervisor/handoff.md`
- `.ai/tasks/_closed/gt-hu-016-evaluacion-tutor/handoff.md`
- `docs/backlog/candidatos-iniciales.md`
- `AI_COLLABORATION.md`
- `project-app/`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
