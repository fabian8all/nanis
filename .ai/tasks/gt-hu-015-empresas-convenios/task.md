# Tarea: gt-hu-015-empresas-convenios

**Creada:** 2026-10-10 · **Tamaño:** medium · **Owner:** backend · **Rol:** Backend (`software/backend`)

## Objetivo
Permitir que Coordinación de prácticas mantenga manualmente el catálogo de empresas que se muestra al alumnado. Implementar después de confirmar qué significa dar de baja una empresa para sus referencias existentes.

## Incluido en el alcance
- Refinar criterios de aceptación de GT-HU-015 con la persona solicitante.
- Diseñar e implementar el modelo y la administración de empresas con los campos y reglas confirmados.
- Agregar pruebas para los criterios confirmados y actualizar el backlog si se precisan reglas.

## Explícitamente fuera de alcance
- La consulta/selección de empresas por el alumno (GT-HU-014).
- La solicitud de práctica (GT-HU-001).
- Inventar reglas de baja/historial no confirmadas.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/app/Models/**`
- `project-app/app/Livewire/**`
- `project-app/app/Http/**`
- `project-app/database/migrations/**`
- `project-app/resources/views/**`
- `project-app/routes/web.php`
- `project-app/tests/Feature/**`
- `docs/backlog/candidatos-iniciales.md`
- `docs/sprints/plan-sprints-candidato.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `docs/formatos/**` (fuente de lectura; no versionar formatos con datos personales sin sanear)
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`
- `.ai/tasks/gt-hu-015-empresas-convenios/**`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md`
- `docs/backlog/candidatos-iniciales.md` (GT-HU-015)
- `docs/sprints/plan-sprints-candidato.md` (Sprint 1 y dependencias)
- `project-app/` (estructura Laravel existente, sin asumir campos)

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
