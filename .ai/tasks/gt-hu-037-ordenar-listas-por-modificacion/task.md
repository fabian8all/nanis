# Tarea: gt-hu-037-ordenar-listas-por-modificacion

**Creada:** 2026-10-14 · **Tamaño:** large · **Owner:** backend (reclamado 2026-10-14)

## Objetivo
Ordenar las listas de registros de negocio por la modificación que el usuario
ve como más reciente, para que el trabajo actualizado aparezca primero. Cuando
la lista representa una versión o revisión relacionada, debe usarse la
modificación de esa versión/revisión y resolver empates con un orden estable.

## Incluido en el alcance
- Inventariar las listas de negocio visibles para Alumno, Coordinación,
  Supervisor y Tutor, incluidas vistas con historial de versiones.
- Registrar por lista la entidad representada, la fecha relevante disponible
  y el criterio de desempate antes de editar código.
- Ordenar descendentemente por la modificación más reciente que corresponda
  al contenido presentado; para versiones/revisiones, usar el timestamp de la
  versión/revisión mostrada aunque el padre no haya cambiado.
- Añadir desempate estable por identificador y pruebas de orden/empates.
- Mantener filtros, autorización, estados y asociaciones actuales.

## Explícitamente fuera de alcance
- Añadir búsqueda o paginación (GT-HU-038).
- Cambiar estados, fechas persistidas, esquema o lógica de negocio.
- Ordenar listas estáticas, opciones de formulario o paneles que no representen
  registros de negocio.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/sprints/plan-sprints-candidato.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`
- No hay archivos de producto autorizados todavía: completar el inventario y
  añadir aquí la lista exacta de componentes, vistas y pruebas antes de editar
  código.

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-037 y refinamiento)
- `docs/v1.1/sprints/plan-sprints-candidato.md` (Sprint 5)
- Inventario de listas y superficies candidatas de `project-app/app/Livewire/`
- Pruebas de los componentes que el inventario identifique

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
