# Plan de ejecución: gt-hu-038-buscar-paginar-listas-restantes

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Inventariar por rol todas las listas del alcance, revisar
  controles existentes y acordar campos de búsqueda por lista.
- [x] Paso 2 — Actualizar el write-set con archivos concretos; implementar
  búsqueda/paginación solo donde falte algún control y añadir pruebas.
- [x] Paso 3 — Ejecutar regresión completa y formato, cerrar
  la tarea y crear el commit de la historia.

## Estado actual
Inventario/write-set completados; se implementaron controles en bandejas de
Coordinación, Supervisor, Tutor y Alumno y se añadieron pruebas focalizadas.
La regresión dirigida pasó (54 tests, 479 assertions); la suite completa pasó
(134 tests, 1077 assertions); Pint pasó en los PHP modificados y Continuum
doctor reportó cero problemas críticos. El comando de unittest indicado en el
protocolo no aplica porque este checkout no contiene directorio raíz `tests/`.
