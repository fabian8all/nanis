# Tarea: gt-hu-033-acciones-presentacion-aceptacion

**Creada:** 2026-10-14 · **Tamaño:** medium · **Owner:** frontend · **Rol:** Frontend (`software/frontend`)

## Objetivo
Dar al Supervisor asignado acceso desde la etapa de aceptación al documento de
presentación y a la carga de aceptación de la práctica correspondiente,
conservando el alcance por empresa y los flujos existentes.

## Incluido en el alcance
- Mostrar las dos acciones desde la etapa de aceptación del Supervisor según
  la presentación elegida para esta historia.
- Reutilizar las rutas y controles de autorización existentes para consultar
  la carta y cargar la aceptación.
- Mantener validación, almacenamiento privado y revisión de Coordinación sin
  alterar el flujo de carga.
- Añadir pruebas de acciones disponibles y de aislamiento por empresa/rol.

## Explícitamente fuera de alcance
- Cambiar la autorización, las reglas de revisión o el almacenamiento de la
  carta de aceptación.
- Cambiar la navegación general del Supervisor (GT-HU-032) o los rótulos de
  Seguimiento (GT-HU-031).

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Services/PracticeTrackingSummary.php`
- `project-app/resources/views/livewire/practice-tracking/index.blade.php`
- `project-app/tests/Feature/PracticeTrackingTest.php`
- `project-app/tests/Feature/SupervisorPracticeAcceptanceTest.php`
- `.ai/tasks/gt-hu-033-acciones-presentacion-aceptacion/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-033)
- `project-app/app/Services/PracticeTrackingSummary.php`
- `project-app/resources/views/livewire/practice-tracking/index.blade.php`
- `project-app/routes/web.php`
- `project-app/tests/Feature/PracticeTrackingTest.php`
- `project-app/tests/Feature/SupervisorPracticeAcceptanceTest.php`

## Refinamiento pendiente
Confirmar si las dos acciones se presentan como botones separados o dentro de
un menú desplegable. No implementar la presentación hasta recibir esa decisión.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
