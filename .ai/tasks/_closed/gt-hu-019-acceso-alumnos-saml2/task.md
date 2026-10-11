# Tarea: gt-hu-019-acceso-alumnos-saml2

**Creada:** 2026-10-11 · **Tamaño:** large · **Owner:** backend · **Rol:** Backend (`software/backend`)

## Objetivo
Permitir que los alumnos inicien sesión con el IDP institucional SAML2 y crear
automáticamente su cuenta local con rol de alumno en el primer acceso válido.
Usar `uCorreo` institucional para autenticar la cuenta local ya existente o,
si es el primer acceso, crear una cuenta con rol de alumno. Esto habilita el
catálogo autenticado de GT-HU-014.

## Incluido en el alcance
- Verificar compatibilidad de la estrategia SAML del ejemplo con Laravel 13/PHP 8.4 y elegir una integración mantenible.
- Implementar inicio de sesión SAML2 de alumno y aprovisionamiento local identificando la cuenta por `uCorreo`.
- Si `uCorreo` ya existe, autenticar al usuario con el perfil/rol existente; si no, crear la cuenta con rol único de alumno.
- Conservar el rol administrado en accesos SAML posteriores, incluido alumno que haya pasado a tutor.
- Proveer una simulación controlada para desarrollo/pruebas sin desactivar validaciones en producción ni incluir credenciales/certificados reales.
- Para el prototipo, simular `uCorreo`, `uNombre`, `sn`, `givenName` y `displayName` mediante las variables `SAML_SIMULATOR_*`; dejar la configuración real del IDP para una etapa posterior.
- Rechazar autenticación/aprovisionamiento cuando la aserción SAML no valida o no trae el correo institucional requerido.
- Correlacionar el ACS POST con un request ID de un solo uso sin depender de que el navegador envíe la cookie de sesión cross-site.
- Añadir pruebas de login, primer aprovisionamiento, reutilización de cuenta, preservación de rol, aserción/atributo inválido y aislamiento del simulador.
- Documentar configuración local y variables necesarias sin secretos.

## Explícitamente fuera de alcance
- Cuentas locales de supervisor, verificación de correo e inicio de sesión de supervisor (GT-HU-020).
- Edición de perfiles y gestión de roles por Coordinación (GT-HU-021).
- Catálogo/selección de empresa (GT-HU-014) y formulario de solicitud (GT-HU-001).
- Configuración de producción con certificado/metadata/secretos reales del IDP institucional.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Http/Controllers/Auth/**`
- `project-app/app/Http/Middleware/**`
- `project-app/app/Enums/**`
- `project-app/app/Models/User.php`
- `project-app/app/Providers/**`
- `project-app/app/Services/**`
- `project-app/bootstrap/app.php`
- `project-app/config/**`
- `project-app/.env.example`
- `project-app/routes/web.php`
- `project-app/database/migrations/**`
- `project-app/resources/views/auth/**`
- `project-app/resources/views/layouts/**`
- `project-app/tests/Feature/**`
- `project-app/README.md`
- `project-app/composer.json` y `project-app/composer.lock` (solo si la integración requiere una dependencia compatible)
- `docs/backlog/candidatos-iniciales.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `docs/sprints/plan-sprints-candidato.md`
- `.ai/tasks/_closed/definir-identidad-y-gestion-usuarios/**` (para reflejar decisiones posteriores)
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/tasks/gt-hu-019-acceso-alumnos-saml2/**`
- `.ai/tasks/gt-hu-014-consulta-seleccion-empresa/**` (solo para reflejar la dependencia y reanudar tras HU-019)

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/backlog/candidatos-iniciales.md` (GT-HU-019)
- `docs/sprints/plan-sprints-candidato.md` (dependencia HU-019 → HU-014)
- `project-app/composer.json`
- `project-app/app/Models/User.php`
- `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `project-app/routes/web.php`
- `/home/fabian/code/redi/redi-app/app/Http/Middleware/VerifyAuthSaml.php`
- `/home/fabian/code/redi/redi-app/app/Listeners/LoginListener.php`
- `/home/fabian/code/redi/redi-app/config/saml2_settings.php`
- `/home/fabian/code/redi/redi-app/composer.json`

## Criterios confirmados
- La persona solicitante eligió `uCorreo` (correo institucional) como identificador único, en lugar de `student_number`/`worker_number`, cuya disponibilidad no está confirmada.
- Si el email SAML ya está registrado en la plataforma, usar el perfil existente; si no, dar de alta como alumno.
- Una cuenta existente se autentica con SAML sin sobrescribir el rol único que Coordinación le haya asignado.
- Reemplazar `is_coordinator` por un único campo `role` (`coordinator`, `student`, `tutor`, `supervisor`) y migrar la autorización existente de Coordinación.
- Usar directamente el toolkit `onelogin/php-saml` compatible con PHP 8.x en vez del wrapper `aacotroneo/laravel-saml2`, que está marcado sin mantenimiento.
- Los valores de `SAML_SIMULATOR_*` en `.env.example` son sintéticos; no versionar datos personales.
- HU-019 es prerequisito de HU-014.
- El ejemplo está en `/home/fabian/code/redi`; no copiar sus IDs numéricos de rol, datos de simulación ni credenciales.

## Pendientes posteriores (integración con IDP real)
- Confirmar con el administrador del IDP el claim real de correo y nombre; ambos son configurables.
- Validar una respuesta firmada real cuando se disponga de metadata y una cuenta de prueba.
- Definir si el cierre de sesión local debe iniciar también SLO institucional; el cierre local está implementado.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
