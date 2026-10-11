# Tarea: gt-hu-001-solicitud-practica

**Creada:** 2026-10-11 · **Tamaño:** large · **Owner:** backend · **Rol:** Backend (`software/backend`)

## Objetivo
Permitir al alumno enviar una solicitud de práctica a una empresa elegida para que Coordinación pueda revisarla en la historia posterior. La solicitud conservará los datos necesarios para preparar la carta de presentación sin tratar datos personales no aprobados.

## Incluido en el alcance
- Formulario autenticado para alumno, con correo UCOL, grado/grupo, fecha de inicio, lugar de práctica, destinatario de la carta, puesto y fecha de término, todos obligatorios.
- Consumir `practice.selected_company_id` de la sesión y validar de nuevo que la empresa exista y siga activa al mostrar/enviar.
- Validar que la fecha de término sea posterior a la de inicio.
- Impedir una segunda solicitud en trámite del mismo alumno.
- Mostrar una confirmación simple al enviar, sin folio visible.
- Permitir edición solo antes del envío o después de que una historia futura de Coordinación la devuelva; al enviar, dejarla en trámite y solo lectura.
- Incluir aceptación explícita e impresión de la carta de no responsiva: fecha actual al imprimir, espacios para completar a mano y firma física; no guardar copia firmada.
- Pruebas para autorización, validación, persistencia, empresa activa, duplicados, aceptación/impresión y bloqueo de edición.
- Mantener Bootstrap/Livewire/Alpine, sin Vite ni Tailwind.

## Explícitamente fuera de alcance
- Revisión/devolución administrativa, comentarios y estados de Coordinación (GT-HU-002).
- Carta de presentación y folio.
- Cargar o conservar copia firmada de la no responsiva.
- Agregar NSS u otros campos no confirmados.
- Modificar o incluir los documentos fuente de `docs/formatos/`.

## Criterios confirmados
- Todos los campos acordados son obligatorios.
- La fecha de término debe ser posterior a la de inicio.
- La empresa seleccionada debe seguir activa al enviar.
- No se permiten solicitudes simultáneas en trámite del mismo alumno.
- Una solicitud enviada solo se edita después de una devolución explícita de Coordinación.
- Tras enviar se muestra un mensaje de éxito sencillo, no folio.
- La carta se imprime con la fecha del día; el alumno completa los espacios a mano y la firma físicamente. El sistema no conserva la copia firmada.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.
- `project-app/routes/web.php`
- `project-app/app/Enums/PracticeRequestStatus.php`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/app/Livewire/Student/PracticeRequests/**`
- `project-app/database/migrations/*practice_requests*`
- `project-app/resources/views/livewire/student/practice-requests/**`
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/resources/views/livewire/student/companies/index.blade.php`
- `project-app/tests/Feature/StudentPracticeRequestTest.php`
- `docs/backlog/candidatos-iniciales.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/tasks/gt-hu-001-solicitud-practica/**`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/backlog/candidatos-iniciales.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `docs/sprints/plan-sprints-candidato.md`
- `.ai/tasks/_closed/gt-hu-014-consulta-seleccion-empresa/handoff.md`
- `project-app/routes/web.php`
- `project-app/app/Models/Company.php`
- `project-app/app/Livewire/Student/Companies/Index.php`
