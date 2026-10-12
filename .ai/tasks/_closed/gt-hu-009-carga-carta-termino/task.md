# Tarea: gt-hu-009-carga-carta-termino

**Creada:** 2026-10-12 · **Tamaño:** large · **Owner:** copilot · **Rol:** Backend (`software/backend`)

## Objetivo
Permitir que el alumno cargue la carta de término y que Coordinación valide su contenido, la acepte o la devuelva para corrección. El sistema conserva un único documento vigente y lo guarda en almacenamiento privado.

## Incluido en el alcance
- Carga por el alumno propietario de su práctica en PDF/JPG/PNG, máximo 20 MB.
- Coordinación revisa los elementos de HU-008, acepta o devuelve con comentario obligatorio.
- Reemplazo y reenvío sin límite; cada reemplazo elimina el archivo previo y deja solo el registro/archivo vigente.
- Estado visible para alumno y Coordinación; acceso restringido al alumno propietario y Coordinación.
- Persistencia, almacenamiento privado, descarga autorizada, rutas Livewire y pruebas de autorización/flujo/validación.

## Explícitamente fuera de alcance
- Contador automático de horas, requisito de las 800 horas o aceptación basada en datos distintos de la carta.
- Historial de versiones de la carta de término (se conserva solo la vigente).
- Cerrar el expediente o mostrar expediente consolidado (HU-012).
- Notificaciones y tablero global de avance (HU-010).
- Encuesta (HU-013).

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Enums/PracticeTerminationLetterStatus.php`
- `project-app/app/Models/PracticeTerminationLetter.php`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/app/Livewire/Student/PracticeRequests/Show.php`
- `project-app/app/Livewire/Coordinator/PracticeTerminationLetters/Index.php`
- `project-app/app/Livewire/Coordinator/PracticeTerminationLetters/Review.php`
- `project-app/app/Livewire/Student/PracticeTerminationLetters/Upload.php`
- `project-app/app/Http/Controllers/PracticeTerminationLetterFileController.php`
- `project-app/database/migrations/2026_10_12_000015_create_practice_termination_letters_table.php`
- `project-app/resources/views/livewire/student/practice-termination-letters/upload.blade.php`
- `project-app/resources/views/livewire/student/practice-requests/show.blade.php`
- `project-app/resources/views/livewire/coordinator/practice-termination-letters/index.blade.php`
- `project-app/resources/views/livewire/coordinator/practice-termination-letters/review.blade.php`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/routes/web.php`
- `project-app/tests/Feature/StudentPracticeTerminationLetterTest.php`
- `project-app/tests/Feature/CoordinatorPracticeTerminationLetterTest.php`
- `docs/backlog/candidatos-iniciales.md`
- `.ai/tasks/gt-hu-009-carga-carta-termino/task.md`
- `.ai/tasks/gt-hu-009-carga-carta-termino/execution-plan.md`
- `.ai/tasks/gt-hu-009-carga-carta-termino/notes.md`
- `.ai/tasks/gt-hu-009-carga-carta-termino/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/tasks/_closed/gt-sprint-5-refinamiento-cierre-expediente-encuesta/handoff.md`
- `.ai/tasks/_closed/gt-hu-008-carta-termino/handoff.md`
- `docs/backlog/candidatos-iniciales.md` (GT-HU-008/009)
- `docs/casos-de-uso/flujo-candidato-practica.md` (carta de término y cierre)
- `project-app/app/Livewire/Supervisor/PracticeAcceptances/Upload.php`
- `project-app/app/Livewire/Coordinator/PracticeAcceptances/Review.php`
- `project-app/app/Http/Controllers/Coordinator/PracticeAcceptanceFileController.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
