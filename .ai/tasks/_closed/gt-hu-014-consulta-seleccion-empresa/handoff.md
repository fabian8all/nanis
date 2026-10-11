# Handoff: gt-hu-014-consulta-seleccion-empresa

## Estado

Tarea Continuum cerrada y archivada en `sprint/1-catalogo-solicitud`, asignada
al rol `frontend`. HU-014 implementó el catálogo de empresas activas y la
selección en sesión. HU-019 habilitó la cuenta de alumno con el simulador local;
el IDP real se integrará después.

## Contexto

- HU-015 implementó el catálogo y el campo `Company.is_active`.
- HU-014 muestra únicamente empresas activas con nombre, dirección, teléfono,
  email y persona de contacto, y permite elegir una para la solicitud.
- La captura de la solicitud corresponde a HU-001 y queda fuera de alcance.
- La persona solicitante indicó que SAML2 aprovisionará nuevos usuarios como
  alumnos; para supervisores, Coordinación registra el correo, la aplicación
  envía un enlace y el supervisor confirma y establece su contraseña.
  Coordinación podrá editar roles/perfiles (incluyendo convertir alumno a
  tutor).
- La cuenta de alumno se autentica con el simulador local del prototipo; cuentas
  locales de supervisor siguen pendientes en HU-020.
- El proyecto de ejemplo accesible `/home/fabian/code/redi` usa
  `aacotroneo/laravel-saml2` y simula SAML con atributos por entorno. Puede
  crear usuarios si `CREATE_NEW_USER_RECORD` está habilitado y selecciona un
  rol mediante ID numérico. Este patrón requiere adaptación; no tomar IDs ni
  fixtures como reglas para `nanis`.

## Dependencia resuelta

La tarea prerequisito `definir-identidad-y-gestion-usuarios` desglosó HU-019
(SAML2/alta automática de alumnos), HU-020 (cuentas locales de supervisor) y
HU-021 (edición de perfiles/roles). Solo HU-019 bloquea HU-014. HU-014 debe
guardar la empresa seleccionada asociada a la cuenta del alumno, sin
implementar la integración de identidad ni la solicitud aquí.

La persona solicitante confirmó que el ID se guarda en la clave de sesión
`practice.selected_company_id` hasta que HU-001 la consuma. No persistirla como
preferencia del perfil ni crear una solicitud desde HU-014. Si la empresa es
desactivada, al volver al catálogo se descarta la selección obsoleta.

## Próximos pasos

1. Mantener el formulario de solicitud fuera de HU-014; HU-001 debe consumir
   `practice.selected_company_id` y volver a validar que la empresa siga activa.
2. Elegir y asignar la siguiente historia del Sprint 1.
