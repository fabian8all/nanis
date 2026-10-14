# Tarea: gt-hu-029-formulario-contextual-empresas

**Creada:** 2026-10-14 · **Tamaño:** medium · **Owner:** frontend · **Rol:** Frontend (`software/frontend`)

## Objetivo
Mantener el catálogo de Coordinación como vista principal y mostrar el
formulario de empresa únicamente al iniciar un alta o editar un registro.
Cancelar o guardar debe cerrar el formulario sin perder los datos existentes
ni alterar validaciones o permisos.

## Incluido en el alcance
- Ocultar el formulario al entrar al catálogo y ofrecer una acción explícita
  para iniciar un alta.
- Abrir el formulario vacío al crear y precargado al editar una empresa.
- Cerrar y limpiar el formulario al cancelar o después de guardar; cancelar no
  persiste cambios.
- Conservar los campos, validaciones, mensajes y autorización actuales.
- Añadir cobertura para estado inicial, alta, edición y cancelación.

## Explícitamente fuera de alcance
- Búsqueda, filtros o paginación del catálogo (GT-HU-030).
- Cambios al modelo, esquema, permisos, asociaciones o estado activo de las
  empresas.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Livewire/Companies/Index.php`
- `project-app/resources/views/livewire/companies/index.blade.php`
- `project-app/tests/Feature/CompanyCatalogTest.php`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-029)
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
