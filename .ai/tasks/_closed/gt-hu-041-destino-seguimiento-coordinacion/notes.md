# Notas de trabajo: gt-hu-041-destino-seguimiento-coordinacion

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- HU-041 debe cubrir la ruta SAML por defecto; la ruta de login local ya lleva
  a Coordinación a Seguimiento y debe conservarse.

## Evidencia recopilada
- `AuthenticatedSessionController` redirige el login local de Coordinación a
  `coordinacion.practice-tracking.index`.
- `SamlSessionController` tenía `home` como destino por defecto de roles no
  estudiantiles; ahora usa Seguimiento para Coordinación y conserva `home` para
  los demás roles hasta que HU-042 trate el Supervisor.
- Regresión dirigida: 25 tests y 137 assertions; Pint --test: dos archivos OK.

## Callejones sin salida (para no repetirlos)
- No ejecutar Artisan como comando genérico dentro de `sh -lc`: el contenedor
  usa la ruta del binario PHP directamente con `docker compose exec`.
