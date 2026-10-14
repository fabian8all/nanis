# Handoff — GT-HU-030

## Resultado
- El catálogo permite buscar por coincidencia parcial del nombre y pagina
  resultados en grupos de 20.
- Una búsqueda incluye únicamente empresas activas; la lista sin búsqueda
  conserva visibles las inactivas para permitir su reactivación.
- Cambiar el término vuelve a la primera página, el orden es estable por
  nombre e identificador, y la vista informa cuando no hay coincidencias.
- La búsqueda y la paginación no cambian estados ni asociaciones.

## Archivos
- `project-app/app/Livewire/Companies/Index.php`
- `project-app/resources/views/livewire/companies/index.blade.php`
- `project-app/tests/Feature/CompanyCatalogTest.php`
- `docs/v1.1/backlog/backlog-candidato.md`
- `docs/v1.1/sprints/plan-sprints-candidato.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`

## Validación
- Regresión de catálogo, incluidas pruebas de `StudentCompanyCatalogTest`:
  13 tests y 83 assertions; pasó.
- Pint en el componente y la prueba de catálogo; pasó.
- El primer intento de reconstruir la imagen agotó el timeout TLS contra
  Docker Hub; el reintento terminó correctamente. La regresión y Pint también
  pasaron en un contenedor efímero de la imagen recién reconstruida.
- El servicio `app` se recreó con esa imagen; `http://localhost:8001` y
  `http://nanis.net` respondieron HTTP 200 por el proxy compartido.

## Pendiente / riesgo
- La tarea está cerrada y archivada. Falta integrar Sprint 4 en `develop` y
  publicar el merge en `origin/develop`.
