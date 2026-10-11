# Plan de ejecución: gt-hu-014-consulta-seleccion-empresa

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Definir como historia separada la identidad/gestión de usuarios; inspeccionar el ejemplo SAML cuando esté disponible y ajustar dependencias.
- [x] Paso 2 — Implementar consulta de empresas activas y selección, sin invadir el formulario de GT-HU-001.
- [x] Paso 3 — Probar flujos y límites, validar la aplicación, actualizar handoff, archivar y comprometer la historia.

## Estado actual
HU-014 completada y archivada. El catálogo de alumno muestra empresas activas en orden
alfabético; la selección se guarda como `practice.selected_company_id` en la
sesión, sin crear preferencia de perfil ni solicitud. Se agregó navegación por
rol y cierre de sesión del alumno. La suite completa pasa con 29 pruebas/128
aserciones; Pint pasa en los archivos PHP de HU-014. `http://nanis.net/`
responde HTTP 200 y el catálogo redirige a login cuando se accede sin sesión.
