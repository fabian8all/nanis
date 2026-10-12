# Handoff: gt-hu-017-implementacion-rubricas

## Objetivo
Implementar gestión Coordinator de rúbricas independientes de supervisor y tutor, borradores, versiones históricas inmutables, publicación y cálculo ponderado, sin flujos de captura de HU-006/HU-016.

## Archivos cambiados
- `project-app/app/Enums/RubricType.php`
- `project-app/app/Models/Rubric.php`, `RubricVersion.php`, `RubricCriterion.php`
- `project-app/app/Services/RubricScoreCalculator.php`
- `project-app/app/Livewire/Coordinator/Rubrics/Manage.php`
- `project-app/database/migrations/2026_10_11_000011_create_rubrics_tables.php`
- `project-app/database/seeders/DatabaseSeeder.php`
- `project-app/resources/views/livewire/coordinator/rubrics/manage.blade.php`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/routes/web.php`
- `project-app/tests/Feature/CoordinatorRubricManagementTest.php`

## Implementación
La ruta Coordinator está protegida con `auth`/`EnsureCoordinator`; el componente vuelve a comprobar el rol en render y métodos. Las rúbricas guardan versiones independientes, una referencia vigente por tipo y criterios con etiqueta, descripción, orden, peso y escala JSON de valores numéricos/etiquetas. El seeder crea supervisor v1 incompleto con exactamente los cuatro nombres aprobados y tutor v1 sin criterios ni escala recuperada. El panel permite leer versiones, crear borrador basado en la última, editar campos/criterios/escala, validar y publicar. La publicación valida dentro de transacción, cambia el puntero vigente y conserva versiones anteriores. El calculator compartido verifica escalas con valores numéricos únicos no negativos, máximo válido, puntajes y pesos total 100; el total es 0–100 normalizado.
Se agregó el enlace “Rúbricas” a la navegación de Coordinación para descubrir la nueva pantalla.

## Validación
- Suite dirigida: 7 pruebas, 29 aserciones aprobadas.
- Suite completa: 69 pruebas, 451 aserciones aprobadas.
- Pint `--test` pasó para los 12 PHP modificados.
- Las pruebas se ejecutaron con una copia del código actual en `/tmp/nanis-rubric-validation` dentro del contenedor app, junto con sus dependencias Composer. El contenedor activo no monta el worktree WSL y su `/var/www/html` no tiene los archivos nuevos; correr las pruebas ahí directamente devolvía “No tests found”.
- Se eliminó la copia temporal de validación al terminar las pruebas.
- `git diff --check` pasó para los cambios de esta tarea respetando finales CRLF preexistentes.
- `continuum doctor` tras archivar reportó 0 problemas críticos y una advertencia por costo del contexto inicial.

## Pendientes y riesgos
- Las versiones se inicializan mediante `db:seed` (el setup existente solo migra); ejecutar el seeder en instalación/despliegue nueva.
- El contenedor de desarrollo activo aún no contiene esta versión del código, y la base activa aún no tiene confirmada la migración 000011 ni el seeding inicial. Para habilitar la interfaz real, reconstruir la imagen desde el entorno Windows/Docker compatible con el contexto WSL, aplicar migraciones y ejecutar `php artisan db:seed`; después revisar los dos borradores y completar/publicar las rúbricas antes de iniciar las evaluaciones.
- Como se aprobó, el primer borrador es estructuralmente incompleto, no copia fiel de formatos institucionales. Coordinación debe completar escalas/pesos/criterios antes de publicar.
- HU-006/HU-016 y la fijación de versión por evaluaciones permanecen fuera de alcance.
- Tarea archivada. Los otros cambios del worktree y los formatos de usuario quedan fuera del commit de esta historia.
