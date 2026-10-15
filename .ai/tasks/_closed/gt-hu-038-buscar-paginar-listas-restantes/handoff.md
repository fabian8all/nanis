# Handoff — GT-HU-038

**Fecha:** 2026-10-15 · **Rol:** backend · **Rama:** `sprint/5-experiencia-transversal-listas`

## Resultado

Se inventariaron las listas por rol y se agregaron búsqueda/paginación a las
bandejas de Coordinación, Supervisor, Tutor y Alumno que carecían de controles.
Se incluyeron historiales globales de aceptación/planes/reportes, las versiones
administrativas de rúbricas y encuestas, las prácticas elegibles para encuestas,
las opciones de tutor y el catálogo de empresas del alumno. Las listas con
controles existentes de Seguimiento y catálogo de Coordinación no recibieron
duplicados.

Los filtros conservan estados y alcance por rol. El cambio de búsqueda reinicia
su paginador; las listas separadas tienen búsqueda/paginación independientes.
La selección de empresa del alumno no se pierde cuando la empresa seleccionada
no aparece en la página o búsqueda actual.

## Validación

- Regresión dirigida: 54 tests, 479 assertions.
- Suite Laravel completa: 134 tests, 1077 assertions.
- Pint: pasó para los PHP modificados.
- `git diff --check`: pasó.
- `python3 tools/continuum doctor`: cero problemas críticos; queda la advertencia
  existente del paquete de arranque superior a 3500 tokens.
- No se ejecutó `python3 -m unittest discover -s tests -t .`: el checkout no
  contiene directorio raíz `tests/`.

## Continuidad

La tarea quedó archivada; falta crear su commit de historia en la rama Sprint 5.
GT-HU-037 ya estaba cerrada; al integrar GT-HU-038, ambas historias del sprint
estarán listas para integrar `sprint/5-experiencia-transversal-listas` en
`develop` y publicar la integración. Preservar `project-app/README.md`, un
cambio local previo que queda fuera de este write-set y del commit de historia.
