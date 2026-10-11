# Notas de trabajo: gt-hu-019-acceso-alumnos-saml2

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- La simulación se conserva solo en `local`/`testing`; no hay ruta de bypass
  disponible en producción.

## Evidencia recopilada
- Historia HU-019 añadida al backlog como prerequisito de HU-014.
- La persona solicitante inicialmente indicó matrícula/ID institucional, pero
  luego cambió la decisión: `uCorreo` (email institucional) será identificador
  único, porque el `student_number`/`worker_number` no está confirmado.
- Una cuenta tiene un rol activo y Coordinación puede convertir alumno a
  tutor; el login SAML posterior no debe reescribir ese rol.
- La persona solicitante aprobó reemplazar `is_coordinator` por un campo único
  `role` con los valores `coordinator`, `student`, `tutor`, `supervisor`, y
  migrar la autorización existente.
- El wrapper `aacotroneo/laravel-saml2` está marcado por su mantenedor como no
  mantenido. `onelogin/php-saml` 4.3.2 se integró directamente; Laravel 13
  genera el redirect y metadata y rechaza una respuesta ACS inválida.
- El ejemplo `/home/fabian/code/redi` usa `aacotroneo/laravel-saml2 ^2.1`,
  Laravel `^8.75` y PHP `^7.3|^8.0`; nanis declara Laravel `^13.17` y PHP
  `^8.3`. Para el prototipo, la simulación es el camino de prueba; el claim real
  y las respuestas firmadas del IDP quedan para una integración posterior.

## Callejones sin salida (para no repetirlos)
- La persona solicitante eligió `uCorreo` como clave única; el claim real debe
  validarse. Una coincidencia aprobada usa el perfil/rol existente, sin
  reemplazar el rol en cada autenticación.
- No exponer el simulador en producción ni copiar certificados/fixtures del
  ejemplo.
- No usar IDs numéricos de rol heredados del proyecto de referencia.
