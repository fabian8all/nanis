# Tarea: gt-hu-004-aceptacion-empresa

**Creada:** 2026-10-11 · **Tamaño:** large · **Owner:** backend (reclamado 2026-10-11)

## Objetivo
Registrar la aceptación formal de una empresa mediante la carga de su carta, manteniendo la solicitud fuera del portal empresarial y asegurando que solo un supervisor invitado por Coordinación pueda acceder a las cartas de presentación de su empresa.

## Incluido en el alcance
- Coordinación invita al supervisor por correo; el enlace de un solo uso verifica posesión del correo y permite establecer nombre y contraseña.
- Una cuenta de supervisor se vincula a una empresa. El login local permite acceso de Coordinación y supervisores verificados, sin alterar el acceso SAML de alumnos.
- El supervisor autenticado ve cartas de presentación emitidas de su empresa pendientes de aceptación; no puede consultar solicitudes, NSS/cuenta de otras empresas ni pantallas de Coordinación.
- El supervisor puede consultar la carta oficial completa de su empresa (incluye NSS y cuenta, conforme a decisión confirmada) y cargar la carta de aceptación en PDF, JPG/JPEG o PNG, máximo 20 MB.
- Los archivos se guardan en almacenamiento privado y solo se sirven a Coordinación y al supervisor autorizado de esa empresa.
- Coordinación revisa cada envío y lo acepta o lo devuelve con comentario obligatorio.
- Guardar cada versión del archivo, su estado y sus decisiones/comentarios sin sobrescribir el historial. Tras aceptar una versión, no se admite otra carga para esa práctica.
- Pruebas de invitación/verificación, autenticación y aislamiento por empresa, carga/validación, almacenamiento, historial y revisión.

## Explícitamente fuera de alcance
- Autoregistro o acceso de empresa sin invitación de Coordinación.
- Acceso empresarial al detalle de la solicitud o a datos fuera de la carta oficial emitida para esa empresa.
- Generar la carta de presentación (GT-HU-002), actualizar solicitudes, plan de trabajo u otros documentos.
- Notificar estados por correo después de enviar; el correo se usa para invitar/verificar.
- Registrar descargas de la carta de presentación o entrega física a la empresa.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/routes/web.php`
- `project-app/app/Enums/UserRole.php`
- `project-app/app/Enums/PracticeAcceptanceStatus.php`
- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/app/Http/Controllers/Coordinator/PracticeAcceptanceFileController.php`
- `project-app/app/Http/Controllers/Supervisor/InvitationController.php`
- `project-app/app/Http/Middleware/EnsureSupervisor.php`
- `project-app/app/Livewire/Coordinator/Companies/InviteSupervisor.php`
- `project-app/app/Livewire/Companies/Index.php`
- `project-app/app/Livewire/Coordinator/PracticeAcceptances/**`
- `project-app/app/Livewire/Supervisor/PracticeLetters/**`
- `project-app/app/Livewire/Supervisor/PracticeAcceptances/**`
- `project-app/app/Mail/SupervisorInvitationMail.php`
- `project-app/app/Models/Company.php`
- `project-app/app/Models/PracticeAcceptanceSubmission.php`
- `project-app/app/Models/SupervisorInvitation.php`
- `project-app/app/Models/User.php`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/Dockerfile`
- `project-app/config/livewire.php`
- `project-app/docker/nginx-proxy.conf`
- `project-app/docker/uploads.ini`
- `project-app/database/migrations/*supervisor_invitations*`
- `project-app/database/migrations/*company_id_on_users*`
- `project-app/database/migrations/*practice_acceptance_submissions*`
- `project-app/resources/views/auth/login.blade.php`
- `project-app/resources/views/livewire/companies/index.blade.php`
- `project-app/resources/views/livewire/coordinator/companies/invite-supervisor.blade.php`
- `project-app/resources/views/auth/supervisor-invitation.blade.php`
- `project-app/resources/views/livewire/coordinator/practice-acceptances/**`
- `project-app/resources/views/livewire/supervisor/practice-letters/**`
- `project-app/resources/views/livewire/supervisor/practice-acceptances/**`
- `project-app/resources/views/emails/supervisor-invitation.blade.php`
- `project-app/tests/Feature/SupervisorInvitationTest.php`
- `project-app/tests/Feature/SupervisorPracticeAcceptanceTest.php`
- `project-app/tests/Feature/CoordinatorPracticeAcceptanceTest.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md`
- `../../../../docs/v1.0/casos-de-uso/flujo-candidato-practica.md`
- `../../../../docs/v1.0/sprints/plan-sprints-candidato.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`
- `.ai/state/topics/resumen.md`
- `.ai/tasks/gt-hu-004-aceptacion-empresa/**`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/tasks/_closed/gt-hu-002-revision-solicitud/handoff.md`
- `.ai/tasks/_closed/gt-hu-003-consulta-carta-alumno/handoff.md`
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md` (GT-HU-004 y GT-HU-020)
- `../../../../docs/v1.0/casos-de-uso/flujo-candidato-practica.md`
- `../../../../docs/v1.0/sprints/plan-sprints-candidato.md`
- `project-app/routes/web.php`
- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/app/Enums/UserRole.php`
- `project-app/app/Models/User.php`
- `project-app/app/Models/Company.php`
- `project-app/app/Models/PracticeRequest.php`
- `project-app/app/Models/PracticeLetter.php`
- `project-app/app/Livewire/Coordinator/PracticeLetters/PrintLetter.php`
- `project-app/config/filesystems.php`
- `project-app/config/mail.php`
- `project-app/tests/Feature/CoordinatorAuthenticationTest.php`
- `project-app/tests/Feature/CoordinatorPracticeRequestReviewTest.php`

## Criterios confirmados
- Invitación desde Coordinación, verificación de correo mediante enlace de un solo uso y contraseña definida por el supervisor.
- El supervisor se asocia a una empresa; solo ve las cartas emitidas de esa empresa pendientes de aceptación, no la solicitud original.
- El supervisor sí ve la carta completa, incluido NSS y cuenta, porque es el documento que la empresa recibirá.
- Aceptación permitida: PDF, JPG/JPEG o PNG, hasta 20 MB.
- Coordinación aprueba o devuelve cada archivo; devolver exige comentario.
- Se conservan las versiones y decisiones/comentarios anteriores; los archivos son privados y no se admiten nuevas cargas después de aceptar.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
