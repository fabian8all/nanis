# Tarea: gt-hu-038-buscar-paginar-listas-restantes

**Creada:** 2026-10-14 · **Tamaño:** large · **Owner:** backend (reclamado 2026-10-14)

## Objetivo
Permitir buscar y paginar las listas de negocio que carezcan de cualquiera de
esos controles, sin duplicar controles existentes ni ampliar el acceso a
registros. El alcance incluye las vistas principales, historiales de versiones,
listas administrativas de rúbricas y opciones de selección.

## Incluido en el alcance
- Inventariar listas visibles por rol y clasificar por búsqueda, paginación,
  campos visibles, filtros y límites de autorización existentes.
- Incorporar controles solo en listas que carezcan de búsqueda o paginación;
  las que ya tienen ambos conservan sus controles sin duplicados.
- Definir campos de búsqueda pertinentes por lista, conservar filtros y
  alcance por rol, reiniciar a la primera página al cambiar búsqueda/filtros y
  mostrar estados vacíos informativos.
- Cubrir bandejas/listas principales, historiales de versiones, administración
  de rúbricas y opciones de selección que representen registros de negocio.
- Añadir pruebas para filtros por rol, reinicio de página, resultados vacíos y
  ausencia de duplicados.

## Explícitamente fuera de alcance
- Cambios a autenticación, autorizaciones, modelos, estados o asociaciones.
- Controles duplicados en listas con búsqueda y paginación funcionales.
- Inventarios o formularios que no sean listas de registros de negocio.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/sprints/plan-sprints-candidato.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`
- No hay archivos de producto autorizados todavía: completar el inventario,
  delimitar campos por lista y añadir el write-set exacto antes de editar código.

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-038 y refinamiento)
- `docs/v1.1/sprints/plan-sprints-candidato.md` (Sprint 5)
- Componentes, vistas y pruebas identificados por el inventario de listas

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
