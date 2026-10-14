# Notas de trabajo: gt-hu-024-catalogo-sin-seleccion-en-tramite

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- La persona solicitante confirmó que una solicitud devuelta puede cambiar de
  empresa; el reenvío debe actualizar el registro devuelto, no crear otro.

## Evidencia recopilada
- `PracticeRequestStatus` tiene `Pending`, `Returned`, `Approved` y
  `Completed`; no existe un estado `Cancelled`.
- Regla confirmada: permiten seleccionar la ausencia de solicitud y el estado
  `Returned`; bloquean `Pending`, `Approved` y `Completed`.
- El componente `StudentPracticeRequests\Show` valida el estado al renderizar,
  al crear y al reenviar una solicitud.
- El catálogo de Alumno ya filtra empresas activas y guarda la selección en
  sesión; el enlace de navegación se agrega en `layouts/app.blade.php`.

## Callejones sin salida (para no repetirlos)
- No basta con ocultar el botón de selección: también hay que bloquear una
  llamada directa a los métodos Livewire y al envío de la solicitud.
- Si hay selección nueva en estado devuelto, debe sustituir la empresa del
  mismo registro; no debe conservar la empresa anterior ignorando la sesión.
