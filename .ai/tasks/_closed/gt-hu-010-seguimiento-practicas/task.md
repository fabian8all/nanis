# Tarea: gt-hu-010-seguimiento-practicas

**Creada:** 2026-10-12 · **Tamaño:** large · **Owner:** copilot · **Rol:** Backend (`software/backend`)

## Objetivo
Dar a Coordinación una vista única por práctica con etapas, estados y pendientes accionables, filtros y búsqueda. Mostrar recordatorios dentro de la plataforma a Coordinación y a la persona responsable del siguiente paso, sin correo.

## Incluido en el alcance
- Filas por práctica con etapas solicitud, aceptación, plan, evaluaciones (Supervisor y Tutor separadas), reporte y cierre.
- Estado por etapa, actor responsable de la siguiente acción y textos de pendientes para todas las prácticas; filtros por estado global de etapa y búsqueda por alumno/empresa.
- Recordatorios visibles dentro de la plataforma con enlace directo a la práctica y acción correspondiente para Coordinación, alumno propietario, supervisor asignado y tutor asignado.
- La fila abre la práctica; no aceptar/devolver directamente desde el tablero.
- No crear progreso basado en horas ni mostrar actividades/reportes intermedios.

## Explícitamente fuera de alcance
- Expediente consolidado, política de cierre y permisos del expediente (HU-012).
- Encuestas (HU-013).
- Alertas por correo/push ni centro persistente de notificaciones genérico.
- Acciones de aprobación/devolución desde la tabla.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Services/PracticeTrackingSummary.php`
- `project-app/app/Livewire/PracticeTracking/Index.php`
- `project-app/resources/views/livewire/practice-tracking/index.blade.php`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/routes/web.php`
- `project-app/tests/Feature/PracticeTrackingTest.php`
- `docs/backlog/candidatos-iniciales.md`
- `.ai/tasks/gt-hu-010-seguimiento-practicas/task.md`
- `.ai/tasks/gt-hu-010-seguimiento-practicas/execution-plan.md`
- `.ai/tasks/gt-hu-010-seguimiento-practicas/notes.md`
- `.ai/tasks/gt-hu-010-seguimiento-practicas/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/tasks/_closed/gt-sprint-5-refinamiento-cierre-expediente-encuesta/handoff.md`
- `docs/backlog/candidatos-iniciales.md` (GT-HU-010)
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `project-app/app/Enums/PracticeRequestStatus.php`
- `project-app/app/Enums/PracticeAcceptanceStatus.php`
- `project-app/app/Enums/PracticePlanStatus.php`
- `project-app/app/Enums/PracticeReportStatus.php`
- `project-app/app/Enums/PracticeEvaluationStatus.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
