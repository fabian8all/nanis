# Tarea: gt-hu-026-expediente-documentos-y-estados

**Creada:** 2026-10-14 · **Tamaño:** medium · **Owner:** frontend · **Rol:** Frontend (`frontend`)

## Objetivo
Ordenar los documentos del expediente por el proceso de práctica y mostrar
insignias que permitan identificar su estado de entrega y aprobación sin
ampliar el acceso de ningún rol.

## Incluido en el alcance
- Mostrar carta de presentación, carta de aceptación, Plan de trabajo, reporte
  final aprobado, evaluaciones y carta de término, en ese orden.
- Mostrar por punto una insignia según el estado real: no entregado, en
  revisión, devuelto, aprobado, enviado o emitido, según el flujo aplicable.
- Conservar el resumen agregado actual de requisitos de cierre y sus reglas
  para emitir la carta de término.
- Mantener el filtro actual de documentos por rol; no habilitar enlaces rotos.
- Añadir pruebas para documentos ausentes, entregados/en revisión y aprobados,
  además del aislamiento entre roles.

## Explícitamente fuera de alcance
- Cambiar requisitos de cierre, transiciones, persistencia o autorización.
- Exponer al Supervisor secciones o estados que no están permitidos actualmente.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Livewire/PracticeRecords/Show.php`
- `project-app/resources/views/livewire/practice-records/show.blade.php`
- `project-app/tests/Feature/PracticeRecordTest.php`
- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/sprints/plan-sprints-candidato.md`
- `.ai/tasks/gt-hu-026-expediente-documentos-y-estados/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-026)
- `project-app/app/Livewire/PracticeRecords/Show.php`
- `project-app/app/Services/PracticeTrackingSummary.php`
- `project-app/resources/views/livewire/practice-records/show.blade.php`
- `project-app/tests/Feature/PracticeRecordTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
