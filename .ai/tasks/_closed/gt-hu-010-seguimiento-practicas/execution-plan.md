# Plan de ejecución: gt-hu-010-seguimiento-practicas

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Derivar estados y responsable siguiente desde flujos actuales; diseñar agregador sin duplicar permisos/transiciones.
- [x] Paso 2 — Construir seguimiento para Coordinación y recordatorios por rol con filtros/búsqueda y enlaces a prácticas.
- [x] Paso 3 — Probar estados, filtros, búsqueda, asignación de responsable y aislamiento por rol; actualizar backlog/handoff, validar y cerrar.

## Estado actual
Completado. `PracticeTrackingSummary` deriva las etapas desde los flujos existentes; el componente Livewire aplica el alcance por rol, filtros y búsqueda. Los recordatorios son un resumen dinámico en la plataforma, no notificaciones persistidas ni correo.

Validación dirigida: `PracticeTrackingTest`, 4 pruebas y 27 aserciones aprobadas; Pint `--test` aprobado para los tres archivos PHP de HU-010.
