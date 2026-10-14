# Handoff: GT-HU-037 — Ordenar listas por modificación

## Resultado

La historia está implementada y validada en la rama
`sprint/5-experiencia-transversal-listas`. Las listas de negocio presentan
primero el registro cuya modificación es más reciente; las versiones e
historiales usan la fecha de la versión/revisión mostrada y los empates se
resuelven por identificador. En filas de solicitudes se considera la fecha más
reciente entre el registro y las relaciones visibles que determinan el
contenido presentado.

## Validación

- Regresión dirigida: 64 tests y 581 assertions, correctos.
- Pint: verificación correcta para los archivos PHP modificados.
- Los cambios de `project-app/README.md` son previos y no forman parte de esta
  historia; deben excluirse del commit.

## Estado para reanudar

La tarea Continuum está archivada en esta carpeta. Registrar el commit de
GT-HU-037 y continuar con GT-HU-038 en el mismo checkout, empezando por
inventariar los campos de búsqueda y controles existentes en listas
principales, historiales, administración de rúbricas y opciones de selección.
No integrar Sprint 5 ni hacer push hasta terminar y validar ambas historias.
