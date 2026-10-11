# Notas de trabajo: gt-hu-014-consulta-seleccion-empresa

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- La selección debe quedar asociada al alumno autenticado y enlazarse con la
  futura solicitud (según la aclaración recibida; falta precisar integración).

## Evidencia recopilada
- GT-HU-014 requiere mostrar solo empresas activas y los cinco datos de empresa
  acordados en GT-HU-015.
- `Company.is_active` ya representa la disponibilidad del catálogo; HU-015
  conserva los registros inactivos en vez de borrarlos.
- En el flujo candidato, la selección precede al llenado de la solicitud
  (GT-HU-001), que queda fuera de esta tarea.
- Requisitos nuevos expresados por la persona solicitante: alta automática
  como alumno al iniciar sesión vía IDP/SAML2; supervisores usan usuario y
  contraseña local, agregados por Coordinación tras confirmar correo; la
  Coordinación puede editar perfiles/roles, incluyendo alumno a tutor.
- La aplicación solo tiene autenticación local de Coordinación por ahora; no
  existe modelo ni flujo de acceso de alumno ni de supervisor.
- El ejemplo accesible `/home/fabian/code/redi` usa `aacotroneo/laravel-saml2`;
  simula atributos SAML mediante variables de entorno y puede crear usuarios
  con un rol identificado numéricamente.

## Callejones sin salida (para no repetirlos)
- No implementar la integración SAML ni la administración de cuentas/roles en
  GT-HU-014; dividirlas a una historia independiente. No copiar su ID de rol
  numérico ni sus valores de simulación.
