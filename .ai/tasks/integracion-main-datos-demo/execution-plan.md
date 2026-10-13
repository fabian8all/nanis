# Plan de ejecución: integracion-main-datos-demo

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Verificar que `origin/main` sea ancestro de `origin/develop` y revisar worktrees/cambios locales.
- [ ] Paso 2 — Registrar continuidad correcta, adelantar `main` por fast-forward y verificar el push.
- [ ] Paso 3 — Volver a `develop` y definir dataset, entidades/etapas y protección de credenciales.
- [ ] Paso 4 — Implementar un seeder idempotente para 20 empresas, 1 coordinador, 5 tutores, 1 supervisor y 50 alumnos, con prácticas ficticias variadas.
- [ ] Paso 5 — Probar distribución, relaciones, repetición del seeder y documentar la ejecución.
- [ ] Paso 6 — Actualizar handoff, cerrar tarea y publicar el trabajo en `develop`; no integrar el seeder a `main` sin nueva autorización.

## Estado actual
Paso 2. `origin/main` está 43 commits detrás de `origin/develop` y es su
ancestro. La memoria quedó alineada con el commit `4409f4a`; falta guardar ese
handoff corregido, hacer el fast-forward/push de `main` y volver al worktree
`develop`. Los cambios ajenos en los tres scripts de Continuum/hooks deben
preservarse.
