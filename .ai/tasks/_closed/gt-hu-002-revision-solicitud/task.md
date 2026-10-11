# Tarea: gt-hu-002-revision-solicitud

**Creada:** 2026-10-11 · **Tamaño:** large · **Owner:** backend · **Rol:** Backend (`software/backend`)

## Objetivo
Permitir que Coordinación revise las solicitudes enviadas por estudiantes, las devuelva con un comentario para corrección o las apruebe para preparar una carta de presentación imprimible y con folio.

## Incluido en el alcance
- Cola autenticada de solicitudes pendientes y vista de detalle para Coordinación.
- Solo decisiones de aprobar o devolver; no hay rechazo definitivo.
- Comentario obligatorio al devolver; conservar historial de decisiones y comentarios incluso tras el reenvío del alumno.
- Al devolver, el alumno ve el comentario y puede corregir/re-enviar la misma solicitud por el flujo de HU-001.
- Incluir en la solicitud el número de IMSS/NSS obligatorio de 11 dígitos y número de cuenta obligatorio de 8 dígitos, cifrados en base de datos, con acceso solo para el alumno propietario y Coordinación; conservarlos en el expediente. Imprimir el número de cuenta como `0000-0000`.
- Aprobar registra la decisión y crea la carta de presentación con folio anual `CP-AAAA-0001`.
- Carta con copia propia de datos; la solicitud original del alumno no se modifica.
- Coordinación puede ajustar para la carta únicamente destinatario, puesto, lugar y fechas; los datos de alumno/empresa permanecen fijos.
- Generar una vista imprimible para que Coordinación firme físicamente la carta. HU-003 se encargará de que el alumno la consulte/descargue.
- Usar el formato oficial de `docs/formatos/CARTA DE PRESENTACIÓN FORMATO EP.docx` como referencia, sin versionar el archivo fuente ni incorporar valores de ejemplo.
- Proteger por rol y validar decisiones, comentario, folio y campos; pruebas para acceso, aprobación/devolución, reenvío, historial, datos no mutados y concurrencia/consistencia del folio.

## Explícitamente fuera de alcance
- Rechazo definitivo de solicitudes.
- Consulta/descarga por parte del alumno (GT-HU-003).
- Registro de entrega/resultado de la carta en la empresa.
- Firma electrónica o almacenamiento de una copia firmada.
- Edición de datos originales de solicitud por Coordinación; sus correcciones quedan solo en la carta.

## Criterios confirmados
- Coordinación solo aprueba o devuelve.
- Para devolver se exige comentario.
- Se conserva el historial de cada decisión/comentario.
- La carta tiene folio anual `CP-AAAA-0001`.
- Se imprime para firma física de Coordinación.
- Solo se pueden ajustar destinatario, puesto, lugar y fechas de carta; se conserva intacta la solicitud.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.
- `project-app/routes/web.php`
- `project-app/app/Enums/PracticeRequestStatus.php`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/app/Models/PracticeRequestReview.php`
- `project-app/app/Models/PracticeLetter.php`
- `project-app/app/Livewire/Coordinator/PracticeRequests/**`
- `project-app/app/Livewire/Student/PracticeRequests/Show.php`
- `project-app/app/Models/PracticeRequestReview.php`
- `project-app/app/Models/PracticeLetter.php`
- `project-app/app/Services/PracticeLetters/**`
- `project-app/database/migrations/*practice_request_reviews*`
- `project-app/database/migrations/*practice_letters*`
- `project-app/database/migrations/*practice_request_nss*`
- `project-app/database/migrations/*practice_request_account_number*`
- `project-app/resources/views/livewire/coordinator/practice-requests/**`
- `project-app/resources/views/livewire/coordinator/practice-letters/**`
- `project-app/resources/views/livewire/student/practice-requests/show.blade.php`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/resources/views/home.blade.php`
- `project-app/tests/Feature/CoordinatorPracticeRequestReviewTest.php`
- `project-app/tests/Feature/StudentPracticeRequestTest.php`
- `project-app/tests/Feature/CoordinatorPracticeRequestReviewTest.php`
- `docs/backlog/candidatos-iniciales.md`
- `docs/sprints/plan-sprints-candidato.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/tasks/gt-hu-002-revision-solicitud/**`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/backlog/candidatos-iniciales.md` (GT-HU-001/002/003)
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `docs/sprints/plan-sprints-candidato.md`
- `.ai/tasks/_closed/gt-hu-001-solicitud-practica/handoff.md`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/app/Enums/PracticeRequestStatus.php`
- `project-app/app/Livewire/Companies/Index.php`
- `project-app/routes/web.php`
