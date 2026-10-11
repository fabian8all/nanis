# Plan de ejecución: gt-hu-015-empresas-convenios

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Acordar datos mínimos de empresa/convenio y reglas de vigencia/historial; no implementar antes de resolver criterios.
- [ ] Paso 2 — Diseñar e implementar el modelo y la administración según los criterios confirmados.
- [ ] Paso 3 — Agregar pruebas, validar el flujo, actualizar handoff, hacer commit y cerrar la tarea.

## Estado actual
Paso 1 completo. Se confirmaron los campos de visualización: nombre de empresa, dirección, teléfono, email y persona de contacto; Coordinación hará altas y bajas manuales. La baja oculta de futuras solicitudes y conserva referencias de las existentes. Paso 2 pendiente: modelar el catálogo sin almacenar una copia firmada de la carta de no responsiva ni asumir campos de vigencia que no se solicitaron.
