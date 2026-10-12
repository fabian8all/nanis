# Plan de ejecución: gt-hu-013-encuesta-satisfaccion

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Refinar el flujo/versionado, fijar write-set y permisos.
- [x] Paso 2 — Implementar versiones, solicitudes, respuestas y vistas por rol.
- [x] Paso 3 — Probar privacidad, estados y unicidad; actualizar memoria, validar y comprometer.

## Estado actual
Implementación y pruebas completadas. Coordinación versiona y publica preguntas
con escala 1–5 y comentario opcional, solicita al supervisor de la aceptación
vigente y consulta las respuestas/cancelaciones. El supervisor autenticado
responde una vez; cancelar es terminal para la práctica. Las respuestas no
participan en el cálculo de cierre de HU-012. Migración 000016 aplicada a
MySQL local.
