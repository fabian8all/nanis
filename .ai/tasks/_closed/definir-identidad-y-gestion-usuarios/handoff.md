# Handoff: definir-identidad-y-gestion-usuarios

## Estado

Tarea de refinamiento completada y archivada, asignada a `gestion-proyecto`.
Definió historias candidatas para identidad/gestión de cuentas y ordenó su
dependencia con HU-014. No hizo cambios de código.

## Requisitos expresados

- Alumnos inician sesión mediante el IDP institucional SAML2; al primer acceso,
  su cuenta local se crea automáticamente con perfil de alumno. La identidad
  debe vincularse por matrícula/identificador institucional inmutable, no por
  email; falta obtener el nombre exacto del atributo SAML.
- Supervisores mantienen usuario/contraseña locales; Coordinación registra su
  correo y la aplicación envía un enlace que el supervisor confirma para
  establecer su contraseña.
- Coordinación edita perfiles/roles, incluyendo cambiar a un usuario alumno a
  tutor. Cada cuenta tiene un solo rol activo; el cambio sustituye al anterior.
- HU-014 debe asociar empresa seleccionada con la cuenta del alumno.

## Ejemplo revisado

La copia de referencia `/home/fabian/code/redi` contiene un flujo SAML
Laravel usando `aacotroneo/laravel-saml2`; también tiene simulación por
variables de entorno. `VerifyAuthSaml` inicia sesión y puede crear cuentas
locales si una variable de entorno está activada; las identifica por email y
asigna rol por ID numérico. La implementación usa convenciones del proyecto
anterior, no decisiones aprobadas para nanis. No copiar IDs, fixtures ni
credenciales.

## Pendiente para historias de implementación

1. Confirmar el nombre del atributo SAML inmutable y recuperación de contraseña para supervisores.
2. Completar campos de perfil editables, restricciones por transición y auditoría.
3. Acordar pruebas y compatibilidad del paquete con Laravel 13/PHP 8.4 en la tarea de implementación HU-019.

HU-014 está en espera en `.ai/tasks/gt-hu-014-consulta-seleccion-empresa/` y
conserva asignación `frontend`. HU-019 se debe implementar primero con una
tarea propia asignada a `backend`; después se puede continuar con HU-014.

El backlog y la propuesta de Sprint 1 distinguen HU-019 (SAML2 de alumnos,
bloqueante para HU-014), HU-020 (cuentas locales de supervisor) y HU-021
(edición de perfiles/roles). Solo HU-019 se incluyó como dependencia del Sprint
1; las otras dos siguen sin sprint asignado.
