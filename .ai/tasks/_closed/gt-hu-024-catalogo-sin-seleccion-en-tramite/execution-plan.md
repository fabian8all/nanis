# Plan de ejecución: gt-hu-024-catalogo-sin-seleccion-en-tramite

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Obtener la decisión de estados que habilitan una nueva
  selección; no cambiar el flujo antes de confirmarla.
- [x] Paso 2 — Implementar la regla tanto en el catálogo como en el envío de
  solicitud y cubrir la matriz acordada con pruebas de interfaz y servidor.
- [x] Paso 3 — Ejecutar las pruebas específicas, revisar formato y registrar
  resultados en el handoff de la historia.

## Estado actual
Completada y validada con la regla confirmada: solo sin solicitud o con
solicitud devuelta se puede seleccionar empresa; pendiente, aprobada y
completada bloquean.
