# Tarea: gt-hu-030-busqueda-paginacion-empresas-disponibles

**Creada:** 2026-10-14 · **Tamaño:** medium · **Owner:** backend · **Rol:** Backend (`software/backend`)

## Objetivo
Permitir que Coordinación encuentre empresas por nombre y recorra resultados
en páginas manejables. Al buscar, solo se incluyen empresas disponibles; sin
búsqueda, el catálogo conserva también las ocultas para que puedan reactivarse.

## Incluido en el alcance
- Filtrar por coincidencia parcial en el nombre, solo cuando hay un término de
  búsqueda; los resultados filtrados incluyen únicamente `is_active = true`.
- Paginar con tamaño fijo de 20 resultados, siguiendo el patrón existente de
  las listas paginadas.
- Volver a la primera página al cambiar la búsqueda y mostrar un estado vacío
  explícito sin coincidencias.
- Mantener orden estable por nombre e identificador para recorrer páginas sin
  omitir registros con nombres repetidos.
- Conservar el catálogo sin filtro con empresas activas e inactivas y no
  cambiar estados, asociaciones ni autorizaciones.
- Añadir pruebas de coincidencias, empresas ocultas, reinicio de página,
  paginación y estado vacío.

## Explícitamente fuera de alcance
- Cambios al CRUD, formulario de alta/edición, modelo, esquema o asociaciones.
- Buscar empresas ocultas o alterar su visibilidad/estado.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Livewire/Companies/Index.php`
- `project-app/resources/views/livewire/companies/index.blade.php`
- `project-app/tests/Feature/CompanyCatalogTest.php`
- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/sprints/plan-sprints-candidato.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-030 y refinamiento)
- `project-app/app/Livewire/Companies/Index.php`
- `project-app/resources/views/livewire/companies/index.blade.php`
- `project-app/tests/Feature/CompanyCatalogTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
