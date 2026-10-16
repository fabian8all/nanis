# Notas de trabajo: gt-hu-052-verificar-rubricas-coordinacion

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- La historia queda satisfecha por la implementación existente si Coordinación
  abre la ruta, el enlace está presente y estudiantes siguen sin acceso.

## Evidencia recopilada
- `routes/web.php` declara `/rubricas` como
  `coordinacion.rubrics.manage` dentro del grupo `auth` + `EnsureCoordinator`.
- El layout incluye el enlace Rúbricas de Coordinación con la misma ruta.
- `CoordinatorNavigationTest` comprueba el enlace y que no aparece para
  estudiantes.
- `CoordinatorRubricManagementTest` comprueba apertura de ruta por
  Coordinación y denegación para estudiantes.
- Pruebas ejecutadas: 11 tests, 52 assertions; todas pasaron.

## Callejones sin salida (para no repetirlos)
- No duplicar cobertura ni cambiar el módulo si las pruebas existentes pasan.
