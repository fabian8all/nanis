# Plan de ejecución: integracion-main-datos-demo

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Verificar que `origin/main` sea ancestro de `origin/develop` y revisar worktrees/cambios locales.
- [x] Paso 2 — Registrar continuidad correcta, adelantar `main` por fast-forward y verificar el push.
- [x] Paso 3 — Volver a `develop` y definir dataset, entidades/etapas y protección de credenciales.
- [x] Paso 4 — Implementar un seeder idempotente para 20 empresas, 1 coordinador, 5 tutores, 1 supervisor y 50 alumnos, con prácticas ficticias variadas.
- [x] Paso 5 — Probar distribución, relaciones, repetición del seeder y documentar la ejecución.
- [x] Paso 6 — Actualizar handoff, cerrar tarea y publicar el trabajo en `develop`; no integrar el seeder a `main` sin nueva autorización.

## Estado actual
Completado. Commit `ce38b44` publicado en `origin/develop`; `main` permanece en
`6bce8e9` hasta nueva autorización. Seeder probado en SQLite y MySQL local; se repitió sin duplicar
registros. Base resultante: 20 empresas, 50 prácticas, 5 tutores, 1 supervisor
y 50 alumnos demo; se preservó una cuenta coordinadora previa además de la
cuenta demo. La migración 000017 se corrigió para limitar nombres de índices
MySQL. `http://nanis.net` responde 200 desde el contenedor reconstruido con el
worktree `develop`; 25 cartas de aceptación están en el volumen privado.
Credenciales y correos acordados permanecen solo en `.env` ignorado; no integrar
el seeder a `main` sin nueva autorización. Preservar cambios ajenos de Continuum.
