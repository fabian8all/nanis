# Tarea: gt-hu-031-rotulos-acciones-seguimiento

**Creada:** 2026-10-14 · **Tamaño:** small · **Owner:** frontend · **Rol:** Frontend (`software/frontend`)

## Objetivo
Hacer que los botones de Seguimiento describan la acción concreta y su destino,
manteniendo la operación y autorización existentes.

## Incluido en el alcance
- Reemplazar `Abrir práctica` por rótulos específicos acordes con cada etapa y
  ruta; en particular, usar `Revisar solicitud` para una solicitud en revisión.
- Mantener la ruta y el comportamiento de cada acción.
- Añadir cobertura para los rótulos representativos de las etapas y para que
  una solicitud pendiente siga abriendo su revisión autorizada.

## Explícitamente fuera de alcance
- Cambiar el flujo de las etapas, sus destinos, los permisos o los filtros de
  Seguimiento.
- Cambiar las navegaciones por rol o las acciones de aceptación del Supervisor
  (GT-HU-033).

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Services/PracticeTrackingSummary.php`
- `project-app/resources/views/livewire/practice-tracking/index.blade.php`
- `project-app/tests/Feature/PracticeTrackingTest.php`
- `.ai/tasks/gt-hu-031-rotulos-acciones-seguimiento/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-031)
- `project-app/app/Services/PracticeTrackingSummary.php`
- `project-app/resources/views/livewire/practice-tracking/index.blade.php`
- `project-app/tests/Feature/PracticeTrackingTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
