# Notas de trabajo: gt-hu-028-navegacion-coordinacion

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- No cambiar nombres de rutas ni permisos; los enlaces actuales son destinos
  válidos para el rol Coordinación.

## Evidencia recopilada
- `layouts/app.blade.php` contiene actualmente cada enlace de Coordinación por
  separado.
- `routes/web.php` define `coordinacion.companies.index`,
  `coordinacion.practice-requests.index` y `coordinacion.rubrics.manage`, entre
  las demás rutas del menú.
- El layout carga Bootstrap Bundle 5.3.3.

## Callejones sin salida (para no repetirlos)
- No agregar rutas nuevas para agrupar enlaces; el menú es una reorganización
  de presentación.
