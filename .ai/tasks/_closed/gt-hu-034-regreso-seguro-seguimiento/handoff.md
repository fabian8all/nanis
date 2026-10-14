# Handoff: gt-hu-034-regreso-seguro-seguimiento

**Fecha:** 2026-10-14 · **Rol:** Backend (`software/backend`)

## Objetivo
Hacer que Supervisión vuelva a Seguimiento desde la carta de presentación, sin
depender de la página desde la que abrió la carta ni modificar el retorno de
Tutoría.

## Archivos revisados
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-034)
- `project-app/routes/web.php`
- `project-app/app/Livewire/Supervisor/PracticeLetters/Show.php`
- `project-app/resources/views/livewire/practice-letters/print-letter.blade.php`
- `project-app/tests/Feature/SupervisorPracticeAcceptanceTest.php`
- `project-app/tests/Feature/PracticeRecordTest.php`

## Archivos modificados
- `project-app/app/Livewire/Supervisor/PracticeLetters/Show.php`
- `project-app/tests/Feature/SupervisorPracticeAcceptanceTest.php`
- `project-app/tests/Feature/PracticeRecordTest.php`

## Decisión(es) tomada(s)
- El destino del botón de Supervisión es la ruta fija
  `supervisor.practice-tracking.index`, también al entrar directamente a la
  carta.
- Se conserva para Tutoría el regreso al expediente que ya tenía.
- No se cambió la autorización de consulta ni la carga de cartas.

## Suposiciones vigentes
- La ruta `supervisor.practice-tracking.index` es el destino solicitado para
  continuar el trabajo de Supervisión.

## Validación
- Ejecutada: PHPUnit en el contenedor para
  `SupervisorPracticeAcceptanceTest.php` y `PracticeRecordTest.php`: 9 tests y
  103 aserciones pasaron sin advertencias.
- Pendiente: ninguna para esta tarea.

## Riesgos / dudas abiertas
- Ninguno identificado.

## Siguiente paso recomendado
Resolver los destinos pendientes de GT-HU-023 antes de implementarla; las
demás historias no bloqueadas de Sprint 1 pueden continuar en serie.
