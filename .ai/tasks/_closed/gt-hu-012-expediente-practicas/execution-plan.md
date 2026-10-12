# Plan de ejecución: gt-hu-012-expediente-practicas

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Revisar permisos y modelos terminales; precisar criterios de completitud y write-set.
- [x] Paso 2 — Implementar vista consolidada y enlaces solo para Coordinación y alumno propietario.
- [x] Paso 3 — Probar expediente incompleto/completo, privacidad por rol y documentos; actualizar memoria, validar, cerrar y comprometer.

## Estado actual
Implementación y validación completadas. El expediente solo permite Coordinación y alumno propietario; presenta los cuatro requisitos por separado, contenido del reporte aprobado, carta aceptada y resultados terminales enviados. El seguimiento y la vista de solicitud enlazan el expediente. Pruebas dirigidas: 23 pruebas/212 aserciones; Pint pasa en los PHP modificados.
