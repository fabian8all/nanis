# Tarea: definir-identidad-y-gestion-usuarios

**Creada:** 2026-10-11 · **Tamaño:** large · **Owner:** gestion-proyecto · **Rol:** Gestión de proyecto (ágil/Scrum) (`comun/gestion-proyecto`)

## Objetivo
Convertir los requisitos de acceso y ciclo de vida de alumnos, supervisores,
tutores y Coordinación en historias implementables y ordenadas por dependencia.
Definir cómo encaja la autenticación institucional SAML2 y el acceso local de
supervisores antes de implementar HU-014.

## Incluido en el alcance
- Inspeccionar el ejemplo local `/home/fabian/code/redi` y documentar el patrón SAML simulado, atributos, aprovisionamiento y límites de compatibilidad.
- Refinar flujos de autenticación y alta para: alumnos por IDP/SAML2, supervisores con acceso local y Coordinación.
- Refinar cómo Coordinación agrega supervisores tras confirmar correo y administra perfiles/roles, incluido cambiar de alumno a tutor.
- Desglosar y añadir al backlog historias candidatas con criterios, preguntas abiertas y dependencias; ajustar la propuesta de sprint sin fechas/estimaciones.
- Definir cuál historia de identidad debe preceder a HU-014 y qué historias de gestión de cuentas son independientes.
- Validar la propuesta con la persona solicitante y actualizar continuidad.

## Explícitamente fuera de alcance
- Implementación de SAML2, autenticación local o administración de usuarios.
- Elegir o instalar paquetes, guardar credenciales/certificados o configurar el IDP real.
- Crear una cuenta de coordinación/supervisor o modificar datos de usuarios en producción.
- Implementación de HU-014 (consulta/selección) y HU-001 (solicitud).

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `docs/backlog/candidatos-iniciales.md`
- `docs/sprints/plan-sprints-candidato.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/tasks/definir-identidad-y-gestion-usuarios/**`
- `.ai/tasks/gt-hu-014-consulta-seleccion-empresa/**`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/backlog/candidatos-iniciales.md` (actores, permisos y épicas)
- `docs/sprints/plan-sprints-candidato.md` (Sprint 1 y dependencias)
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `.ai/tasks/gt-hu-014-consulta-seleccion-empresa/handoff.md`
- `/home/fabian/code/redi/redi-app/app/Http/Middleware/VerifyAuthSaml.php`
- `/home/fabian/code/redi/redi-app/app/Listeners/LoginListener.php`
- `/home/fabian/code/redi/redi-app/composer.json`

## Requisitos expresados
- Alumno: autenticación del IDP institucional SAML2 y alta local automática con perfil de alumno al iniciar sesión por primera vez. Decisión posterior: `uCorreo` (email institucional) como identificador único en lugar de `student_number`/`worker_number`; verificar el claim real del IDP.
- Supervisor: conservar inicio de sesión local con usuario y contraseña; Coordinación registra su correo, la aplicación envía un enlace y el supervisor confirma y establece su propia contraseña.
- Coordinación: editar el perfil de otros usuarios y cambiar roles, incluyendo alumno a tutor. Cada cuenta mantiene un único rol activo; cambiarlo reemplaza el rol anterior.
- No reutilizar directamente datos de simulación, IDs numéricos de rol ni credenciales del proyecto de ejemplo.

## Decisiones abiertas
- Nombre/formato real del claim SAML de `uCorreo`.
- Recuperación/cambio posterior de contraseña local para supervisores.
- Campos de perfil editables, matriz de roles y acciones permitidas por rol; si debe quedar auditoría de cambios.
- Si el cambio de rol debe dejar auditoría visible a Coordinación.
- Si las tres capacidades deben ser historias separadas y cuál es el mínimo bloqueante para HU-014.
- Compatibilidad del paquete de ejemplo con Laravel 13/PHP 8.4; no asumir que puede copiarse tal cual.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
