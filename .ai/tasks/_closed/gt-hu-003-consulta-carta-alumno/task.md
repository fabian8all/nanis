# Tarea: gt-hu-003-consulta-carta-alumno

**Creada:** 2026-10-11 · **Tamaño:** medium · **Owner:** backend (reclamado 2026-10-11)

## Objetivo
Permitir que cada alumno consulte e imprima/guarde como PDF la carta de presentación aprobada de su propia solicitud, para entregarla a la empresa.

## Incluido en el alcance
- Mostrar el acceso a la carta desde la solicitud aprobada del alumno.
- Proveer una vista imprimible de la carta para abrir el diálogo del navegador y guardarla como PDF.
- Restringir el acceso a estudiantes autenticados y propietarios de la solicitud; devolver 404 si no existe carta aprobada para esa solicitud.
- Reutilizar la misma representación imprimible para Coordinación y alumno sin exponer cartas ajenas.
- No registrar consultas, descargas ni entrega a empresa, según decisión confirmada.
- Probar autorización por rol/propietario, acceso antes/después de aprobación, renderizado y acción de impresión.

## Explícitamente fuera de alcance
- Registrar eventos de consulta/descarga, fecha o resultado de entrega.
- Generar/almacenar archivos PDF en el servidor o copia firmada.
- Cambiar los datos de la carta o el flujo de aprobación de Coordinación.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/routes/web.php`
- `project-app/app/Livewire/Student/PracticeLetters/**`
- `project-app/app/Livewire/Student/PracticeRequests/Show.php`
- `project-app/app/Livewire/Coordinator/PracticeLetters/PrintLetter.php`
- `project-app/app/Models/PracticeLetter.php`
- `project-app/resources/views/livewire/student/practice-letters/**`
- `project-app/resources/views/livewire/coordinator/practice-letters/print-letter.blade.php`
- `project-app/resources/views/livewire/student/practice-requests/show.blade.php`
- `project-app/resources/views/livewire/practice-letters/**`
- `project-app/tests/Feature/StudentPracticeLetterTest.php`
- `project-app/tests/Feature/StudentPracticeRequestTest.php`
- `project-app/tests/Feature/CoordinatorPracticeRequestReviewTest.php`
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/tasks/gt-hu-003-consulta-carta-alumno/**`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/tasks/_closed/gt-hu-002-revision-solicitud/handoff.md`
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md` (GT-HU-003)
- `../../../../docs/v1.0/sprints/plan-sprints-candidato.md`
- `project-app/routes/web.php`
- `project-app/app/Livewire/Coordinator/PracticeLetters/PrintLetter.php`
- `project-app/app/Models/PracticeLetter.php`
- `project-app/app/Livewire/Student/PracticeRequests/Show.php`
- `project-app/resources/views/livewire/student/practice-requests/show.blade.php`
- `project-app/tests/Feature/CoordinatorPracticeRequestReviewTest.php`

## Criterios confirmados
- Una carta solo está disponible al alumno propietario después de aprobarse la solicitud.
- El alumno podrá consultarla e imprimirla o guardarla como PDF desde el navegador.
- El sistema no registra eventos de consulta/descarga ni fechas/resultados de entrega.
- Coordinación conserva su acceso de impresión protegido.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
