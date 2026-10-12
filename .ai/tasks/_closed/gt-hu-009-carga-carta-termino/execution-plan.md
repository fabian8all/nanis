# Plan de ejecución: gt-hu-009-carga-carta-termino

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Definir tabla, estados, permisos y flujo privado de carga/revisión, respetando el reemplazo único de la carta.
- [x] Paso 2 — Implementar vistas/rutas Livewire y descarga autorizada de archivo, siguiendo patrones de aceptación y reporte.
- [x] Paso 3 — Probar carga, tamaño/tipos, corrección, aceptación, aislamiento de roles y cleanup; actualizar docs/handoff, validar y cerrar.

## Estado actual
Terminada: 9 pruebas dirigidas y 70 aserciones pasan en SQLite en memoria; Pint pasa para los 11 PHP afectados. Migración pendiente de ejecutar en la base local al desplegar.
