# Notas de trabajo: definir-identidad-y-gestion-usuarios

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- La identidad de alumno por SAML2 es prerequisito de HU-014; la selección
  quedará asociada a la cuenta del alumno.

## Evidencia recopilada
- Persona solicitante requiere alta local automática como alumno al primer
  acceso SAML2, vinculada por matrícula/identificador institucional inmutable
  y no por email. El nombre exacto del atributo SAML está pendiente.
- Supervisores deben conservar autenticación local usuario/contraseña y ser
  agregados por Coordinación tras confirmar correo.
- Aclaración: Coordinación registra el correo; la aplicación envía un enlace y
  el supervisor lo confirma y establece su contraseña.
- Coordinación debe editar perfiles/roles, incluido cambio de alumno a tutor.
- Cada cuenta mantiene un único rol activo; cambiar alumno a tutor reemplaza el
  rol previo.
- Solo el acceso SAML2/alta automática de alumnos (HU-019) debe bloquear
  HU-014. Supervisor local (HU-020) y edición de perfil/roles (HU-021) pueden
  planificarse después y aún no tienen sprint asignado.
- El proyecto de ejemplo usa `aacotroneo/laravel-saml2` y `VerifyAuthSaml`
  admite simulación por variables de entorno. En el modo de alta condicional,
  busca por email, crea el registro y asigna un rol por ID numérico. No tomar
  como decisiones de nanis el mapeo por email, el ID de rol ni las credenciales
  o valores de fixture.
- Nanis usa Laravel 13/PHP 8.4 y hoy solo cuenta con login local de Coordinación.

## Callejones sin salida (para no repetirlos)
- No copiar directamente el middleware heredado ni hardcodear el ID de rol
  encontrado en el ejemplo.
- No incluir valores de identidad, certificados ni credenciales del ejemplo en
  la memoria/documentación de nanis.
