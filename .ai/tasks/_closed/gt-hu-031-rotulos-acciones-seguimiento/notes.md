# Notas de trabajo: gt-hu-031-rotulos-acciones-seguimiento

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- Los rótulos deben reflejar el destino ya autorizado, sin cambiar qué rol
  puede abrirlo.

## Evidencia recopilada
- `PracticeTrackingSummary` construye los estados, detalles, responsables y
  rutas de cada etapa.
- `livewire/practice-tracking/index.blade.php` renderiza actualmente el texto
  genérico `Abrir práctica`.
- `PracticeTrackingTest` cubre Coordinación, Alumno y Supervisor en
  Seguimiento.

## Callejones sin salida (para no repetirlos)
- No cambiar las rutas en el helper para resolver una tarea de presentación.
