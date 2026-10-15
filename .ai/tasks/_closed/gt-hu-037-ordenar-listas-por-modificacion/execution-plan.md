# Plan de ejecución: gt-hu-037-ordenar-listas-por-modificacion

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Inventariar listas visibles por rol y definir el dato de
  modificación que representa cada lista, incluidos historiales/versiones.
- [x] Paso 2 — Actualizar el write-set con archivos concretos; implementar
  orden descendente y desempate estable con pruebas de regresión.
- [x] Paso 3 — Ejecutar pruebas dirigidas y formato, y archivar la tarea.

## Estado actual
Completados los tres pasos. Las listas se ordenan por `updated_at` del registro
o versión mostrada, con desempate por ID; las filas de solicitudes consideran
la actualización más reciente del padre y de las relaciones visibles. La
regresión dirigida pasó con 64 tests y 581 assertions; Pint pasó para los PHP
modificados, la tarea está archivada y la historia quedó en `f23d463`.
Siguiente: continuar con el inventario de búsqueda de GT-HU-038.
