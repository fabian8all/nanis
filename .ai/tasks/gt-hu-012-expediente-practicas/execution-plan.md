# Plan de ejecución: gt-hu-012-expediente-practicas

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [ ] Paso 1 — Revisar permisos y modelos terminales; precisar criterios de completitud y write-set.
- [ ] Paso 2 — Implementar vista consolidada y enlaces solo para Coordinación y alumno propietario.
- [ ] Paso 3 — Probar expediente incompleto/completo, privacidad por rol y documentos; actualizar memoria, validar, cerrar y comprometer.

## Estado actual
Paso 1 en curso. La tarea está creada y reclamada; el análisis encontró las relaciones en `PracticeRequest`, las rutas separadas por rol de consulta de evaluaciones y controladores privados existentes para la carta de término y anexos del reporte. Todavía no se modificó código de la aplicación. Falta continuar revisando permisos/modelos y después implementar la pantalla.
