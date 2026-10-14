# Tarea: gt-hu-036-observaciones-por-seccion

**Creada:** 2026-10-14 · **Tamaño:** medium · **Owner:** frontend · **Rol:** Frontend (`frontend`)

## Objetivo
Permitir que el Tutor consulte bajo demanda las observaciones específicas de
cada sección del reporte sin ocultar el comentario general obligatorio.

## Incluido en el alcance
- Mantener inicialmente ocultas las áreas de observación por sección.
- Proveer un control identificable y accesible para mostrar u ocultar cada
  observación debajo de su campo de reporte.
- Conservar el contenido de observaciones al ocultarlas y al guardar la
  revisión.
- Mantener visible el comentario general y su obligatoriedad al devolver el
  reporte; las observaciones por sección permanecen opcionales.
- Añadir pruebas de interacción, persistencia y obligatoriedad del comentario
  general.

## Explícitamente fuera de alcance
- Cambiar el esquema de almacenamiento, el flujo de revisión o la matriz de
  permisos.
- Cambiar el formulario del Alumno o los comentarios de otras etapas.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/resources/views/livewire/tutor/practice-reports/review.blade.php`
- `project-app/tests/Feature/TutorPracticeReportReviewTest.php`
- `docs/v1.1/backlog/backlog-candidato.md`
- `.ai/tasks/gt-hu-036-observaciones-por-seccion/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-036)
- `project-app/app/Livewire/Tutor/PracticeReports/Review.php`
- `project-app/resources/views/livewire/tutor/practice-reports/review.blade.php`
- `project-app/tests/Feature/TutorPracticeReportReviewTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
