# Plan de ejecución: definir-identidad-y-gestion-usuarios

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Analizar el ejemplo SAML y refinar necesidades, atributos, roles y ciclo de vida con la persona solicitante.
- [x] Paso 2 — Separar historias candidatas, criterios, dependencias y propuesta de Sprint 1 en backlog/documentación.
- [x] Paso 3 — Validar la propuesta, actualizar handoffs, confirmar write-set, hacer commit y cerrar la tarea.

## Estado actual
Tarea completada. Se definieron HU-019 (SAML2 + alta automática de
alumnos; prerequisito de HU-014), HU-020 (supervisores locales con verificación
de correo) y HU-021 (gestión de perfiles/roles). La persona solicitante aprobó
que solo HU-019 bloquee HU-014, y definió identificador institucional
inmutable/rol único y verificación del correo con enlace + contraseña definida
por el supervisor. El atributo SAML exacto, campos editables, recuperación de
contraseña y auditoría quedan abiertos. Se actualizaron backlog, flujo y plan
candidato. La implementación de HU-019 requiere una tarea independiente
asignada a `backend`.
