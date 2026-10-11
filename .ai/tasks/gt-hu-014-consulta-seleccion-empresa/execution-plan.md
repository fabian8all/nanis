# Plan de ejecución: gt-hu-014-consulta-seleccion-empresa

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Definir como historia separada la identidad/gestión de usuarios; inspeccionar el ejemplo SAML cuando esté disponible y ajustar dependencias.
- [ ] Paso 2 — Implementar consulta de empresas activas y selección, sin invadir el formulario de GT-HU-001.
- [ ] Paso 3 — Probar flujos y límites, validar la aplicación, actualizar handoff, hacer commit y cerrar la tarea.

## Estado actual
Paso 2 en curso. HU-019 implementó login SAML mediante OneLogin, aprovisionamiento
de alumnos y simulación local con variables `SAML_SIMULATOR_*`; datos reales del
IDP se configurarán después. La dependencia de identidad queda resuelta para
desarrollar HU-014; implementar selección sin incluir el formulario de HU-001.
