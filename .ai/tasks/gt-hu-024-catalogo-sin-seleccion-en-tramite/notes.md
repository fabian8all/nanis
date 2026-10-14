# Notas de trabajo: gt-hu-024-catalogo-sin-seleccion-en-tramite

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- No asumir que `Completed` permite otra selección: requiere confirmación.

## Evidencia recopilada
- `PracticeRequestStatus` tiene `Pending`, `Returned`, `Approved` y
  `Completed`; no existe un estado `Cancelled`.
- El componente `StudentPracticeRequests\Show` valida el estado al renderizar,
  al crear y al reenviar una solicitud.
- El catálogo de Alumno ya filtra empresas activas y guarda la selección en
  sesión; el enlace de navegación se agrega en `layouts/app.blade.php`.

## Callejones sin salida (para no repetirlos)
- No basta con ocultar el botón de selección: también hay que bloquear una
  llamada directa a los métodos Livewire y al envío de la solicitud.
