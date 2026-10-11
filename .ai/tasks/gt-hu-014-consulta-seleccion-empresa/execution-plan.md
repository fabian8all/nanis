# Plan de ejecución: gt-hu-014-consulta-seleccion-empresa

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [ ] Paso 1 — Definir como historia separada la identidad/gestión de usuarios; inspeccionar el ejemplo SAML cuando esté disponible y ajustar dependencias.
- [ ] Paso 2 — Implementar consulta de empresas activas y selección, sin invadir el formulario de GT-HU-001.
- [ ] Paso 3 — Probar flujos y límites, validar la aplicación, actualizar handoff, hacer commit y cerrar la tarea.

## Estado actual
Paso 1 en curso. La tarea quedó creada en la rama `sprint/1-catalogo-solicitud`
y asignada al rol `frontend`. HU-015 está completada. La persona solicitante
aclaró que los alumnos iniciarán sesión mediante SAML2 y se aprovisionarán con
perfil de alumno, mientras supervisores mantendrán acceso local y Coordinación
administrará perfiles/roles. El ejemplo `/home/fabian/code/redi` fue
inspeccionado; usa `aacotroneo/laravel-saml2`, simulación por variables de
entorno y alta condicional con rol indicado por ID numérico. La identidad y la
selección asociada a cuenta requieren una historia previa, fuera de este
write-set. No comenzar implementación de HU-014 hasta acordar la historia
prerequisito y su lugar en Sprint 1.
