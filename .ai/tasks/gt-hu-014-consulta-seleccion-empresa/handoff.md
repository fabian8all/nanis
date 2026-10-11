# Handoff: gt-hu-014-consulta-seleccion-empresa

## Estado

Tarea Continuum abierta en `sprint/1-catalogo-solicitud`, asignada al rol
`frontend`. La fase de refinamiento está en curso; HU-014 depende ahora de que
se defina e implemente identidad del alumno.

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
- No existe autenticación/cuenta de alumno ni de supervisor implementada.
- El proyecto de ejemplo accesible `/home/fabian/code/redi` usa
  `aacotroneo/laravel-saml2` y simula SAML con atributos por entorno. Puede
  crear usuarios si `CREATE_NEW_USER_RECORD` está habilitado y selecciona un
  rol mediante ID numérico. Este patrón requiere adaptación; no tomar IDs ni
  fixtures como reglas para `nanis`.

## Decisión pendiente

La tarea prerequisito `definir-identidad-y-gestion-usuarios` desglosó HU-019
(SAML2/alta automática de alumnos), HU-020 (cuentas locales de supervisor) y
HU-021 (edición de perfiles/roles). Solo HU-019 bloquea HU-014. HU-014 debe
guardar la empresa seleccionada asociada a la cuenta del alumno, sin
implementar la integración de identidad ni la solicitud aquí.

El ejemplo ya fue inspeccionado en `/home/fabian/code/redi`. Antes de basar la
integración en él, definir aparte el modelo de roles/provisión y validar la
compatibilidad de su paquete con la aplicación actual.

## Próximos pasos

1. Implementar y validar HU-019 con una tarea propia.
2. Ajustar los criterios de HU-014 e implementarla después.
3. Validar, actualizar handoff y cerrar con commit de la historia.
