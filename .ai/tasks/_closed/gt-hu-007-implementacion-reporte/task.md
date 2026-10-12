# Tarea: gt-hu-007-implementacion-reporte

**Creada:** 2026-10-12 · **Tamaño:** large · **Owner:** backend · **Rol:** (sin asignar)

## Objetivo
Implementar la entrega, versionado, revisión por tutor y cierre del reporte final (GT-HU-007), incluyendo anexos privados y su relación con HU-016.

## Incluido en el alcance
- Captura estructurada del reporte por el alumno, borradores y reenvíos con historial inmutable.
- Anexos privados PDF/JPG/PNG, múltiples y de hasta 20 MB cada uno; validación y acceso autorizado.
- Bandeja/revisión del tutor asignado, devolución con comentario general obligatorio y observaciones opcionales por sección, aprobación y reapertura solo por Coordinación.
- Persistencia de tutor asignado si hace falta para autorizar la revisión; no implementar administración general de usuarios.
- Pruebas de autorización, propiedad, estados, versiones, uploads y aislamiento entre prácticas.

## Explícitamente fuera de alcance
- Evaluación de supervisor HU-006 y evaluación del tutor HU-016.
- Edición/publicación de rúbricas HU-017.
- Reportes de avance o límite agregado de anexos.
- Incorporar a git documentos fuente o ejemplos institucionales con datos personales.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/gt-hu-007-implementacion-reporte/task.md`
- `.ai/tasks/gt-hu-007-implementacion-reporte/notes.md`
- `.ai/tasks/gt-hu-007-implementacion-reporte/execution-plan.md`
- `.ai/tasks/gt-hu-007-implementacion-reporte/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `docs/backlog/candidatos-iniciales.md`
- `project-app/app/Enums/`
- `project-app/app/Models/`
- `project-app/app/Livewire/Student/`
- `project-app/app/Livewire/Tutor/`
- `project-app/app/Livewire/Coordinator/`
- `project-app/app/Http/Middleware/`
- `project-app/app/Http/Controllers/`
- `project-app/database/migrations/`
- `project-app/resources/views/livewire/`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/routes/web.php`
- `project-app/tests/Feature/`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `.ai/tasks/_closed/gt-hu-007-reporte-final/handoff.md`
- `.ai/tasks/_closed/gt-hu-016-evaluacion-tutor/handoff.md`
- `.ai/tasks/_closed/gt-hu-017-implementacion-rubricas/handoff.md`
- `docs/backlog/candidatos-iniciales.md`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/app/Enums/UserRole.php`
- `AI_COLLABORATION.md`

## Bloqueos antes de implementar
- Resuelto: la persona solicitante aprobó la estructura simplificada propuesta (datos de estancia: empresa, área y periodo; objetivos; actividades realizadas; resultados/aprendizajes; conclusiones; recomendaciones; anexos).
- Resuelto: Coordinación asigna tutor a la práctica mediante `tutor_id`; la revisión se autoriza exclusivamente al tutor asignado.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
