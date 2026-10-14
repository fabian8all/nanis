# Notas de trabajo: gt-hu-032-documentos-supervisor

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- La agrupación es solo navegación; las páginas existentes siguen siendo las
  responsables de limitar los datos al Supervisor y su empresa.

## Evidencia recopilada
- El layout muestra actualmente Seguimiento más cuatro enlaces individuales.
- `routes/web.php` define las rutas de cartas, planes, evaluaciones y encuestas
  bajo `supervisor.*` y `EnsureSupervisor`.

## Callejones sin salida (para no repetirlos)
- No crear una ruta de menú ni duplicar controles de autorización.
