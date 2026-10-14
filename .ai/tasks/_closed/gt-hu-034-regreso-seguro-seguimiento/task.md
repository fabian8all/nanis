# Tarea: gt-hu-034-regreso-seguro-seguimiento

**Creada:** 2026-10-14 · **Tamaño:** medium · **Owner:** backend · **Rol:** Backend (`software/backend`)

## Objetivo
Permitir que Supervisión regrese a Seguimiento después de consultar una carta
de presentación, tanto si llegó desde Seguimiento como si abrió la carta
directamente.

## Incluido en el alcance
- El regreso desde la carta dirige a Seguimiento de Supervisión, no al
  expediente restringido.
- La navegación funciona al entrar desde Seguimiento y mediante acceso directo
  a la carta, sin depender de una página de origen.
- El destino no permite redirecciones arbitrarias ni omite autorizaciones.
- Conservar el destino actual de Tutoría; el cambio aplica al regreso de
  Supervisión.
- Añadir pruebas para los flujos de acceso y el destino de regreso.

## Dependencias
- Consulta autorizada de cartas y Seguimiento: GT-HU-003 y GT-HU-010.

## Explícitamente fuera de alcance
- Cambiar quién puede consultar cartas o expedientes.
- Cambiar el flujo de carga de la carta de aceptación.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Livewire/Supervisor/PracticeLetters/Show.php`
- `project-app/resources/views/livewire/practice-letters/print-letter.blade.php`
- `project-app/tests/Feature/SupervisorPracticeAcceptanceTest.php`
- `project-app/tests/Feature/PracticeRecordTest.php`
- `.ai/tasks/gt-hu-034-regreso-seguro-seguimiento/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-034)
- `project-app/routes/web.php`
- `project-app/app/Livewire/Supervisor/PracticeLetters/Show.php`
- `project-app/resources/views/livewire/practice-letters/print-letter.blade.php`
- `project-app/tests/Feature/SupervisorPracticeAcceptanceTest.php`
- `project-app/tests/Feature/PracticeRecordTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
