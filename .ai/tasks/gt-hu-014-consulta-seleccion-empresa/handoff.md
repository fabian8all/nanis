# Handoff: gt-hu-014-consulta-seleccion-empresa

## Estado

Tarea Continuum abierta en `sprint/1-catalogo-solicitud`, asignada al rol
`frontend`. HU-019 ya implementó acceso de alumno por SAML con un simulador
local configurado mediante `SAML_SIMULATOR_*`; el IDP real se integrará después.
HU-014 puede iniciar su implementación de consulta/selección de empresa activa.

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

## Próximos pasos

1. Implementar catálogo de empresas activas y selección asociada al usuario alumno.
2. Mantener el formulario de solicitud fuera de HU-014.
3. Validar con el simulador local, actualizar handoff y cerrar con commit de la historia.
