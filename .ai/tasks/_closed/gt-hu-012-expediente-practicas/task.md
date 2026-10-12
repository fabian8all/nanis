# Tarea: gt-hu-012-expediente-practicas

**Creada:** 2026-10-12 · **Tamaño:** large · **Owner:** copilot (reclamado 2026-10-12)

## Objetivo
Dar acceso a un expediente consolidado por práctica con la evidencia terminal acordada. Alumno propietario y Coordinación consultan el expediente completo y su estado de cierre; Tutor y Supervisor no reciben acceso al expediente.

## Incluido en el alcance
- Mostrar carta de término aceptada, reporte final aprobado y las evaluaciones terminales de Supervisor y Tutor; señalar explícitamente los elementos pendientes.
- Considerar completo/cerrado el expediente solo cuando la carta esté aceptada, ambas evaluaciones estén enviadas y exista reporte aprobado.
- Restringir la vista a Coordinación y al alumno propietario; documentos adjuntos siguen usando sus controladores privados y autorizaciones existentes.
- Enlazar el expediente desde el seguimiento y las vistas pertinentes; permitir a cada rol consultante abrir sus flujos/documentos autorizados.

## Explícitamente fuera de alcance
- Encuesta de satisfacción (HU-013), que no bloquea el cierre.
- Cambios a carga/revisión de carta de término (HU-009), al reporte/evaluaciones terminales, o a sus reglas de almacenamiento.
- Acceso al expediente completo para Tutor/Supervisor; estos conservan solo los accesos existentes a sus propias evaluaciones.
- Contador independiente de horas o nuevos requisitos documentales.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Livewire/PracticeRecords/Show.php`
- `project-app/resources/views/livewire/practice-records/show.blade.php`
- `project-app/routes/web.php`
- `project-app/app/Services/PracticeTrackingSummary.php`
- `project-app/resources/views/livewire/student/practice-requests/show.blade.php`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/tests/Feature/PracticeRecordTest.php`
- `project-app/tests/Feature/PracticeTrackingTest.php`
- `docs/backlog/candidatos-iniciales.md`
- `.ai/tasks/gt-hu-012-expediente-practicas/task.md`
- `.ai/tasks/gt-hu-012-expediente-practicas/execution-plan.md`
- `.ai/tasks/gt-hu-012-expediente-practicas/notes.md`
- `.ai/tasks/gt-hu-012-expediente-practicas/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/tasks/_closed/gt-sprint-5-refinamiento-cierre-expediente-encuesta/handoff.md`
- `docs/backlog/candidatos-iniciales.md` (GT-HU-012)
- `project-app/app/Models/PracticeRequest.php` y relaciones terminales
- `project-app/routes/web.php` (perfiles y archivos privados)
- Controladores privados de carta de término y anexos de reporte

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
