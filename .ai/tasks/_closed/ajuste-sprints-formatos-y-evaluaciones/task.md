# Tarea: ajuste-sprints-formatos-y-evaluaciones

**Creada:** 2026-10-11 · **Tamaño:** large · **Owner:** gestion-proyecto · **Rol:** Gestión de proyecto (ágil/Scrum) (`comun/gestion-proyecto`)

## Objetivo
Actualizar el backlog y la secuencia de sprints con los formatos compartidos, que identifican evaluaciones separadas de supervisor y tutor, además de una carta de no responsiva para la solicitud. Mantener las decisiones confirmadas y las incógnitas de implementación claramente separadas.

## Incluido en el alcance
- Revisar los formatos entregados en `docs/formatos/` como fuentes de requisitos.
- Desglosar en historias las dos evaluaciones y la configuración de rúbricas por Coordinación.
- Añadir al flujo de solicitud la aceptación e impresión de la carta de no responsiva.
- Actualizar backlog, plan candidato y continuidad de Continuum con dependencias y preguntas pendientes.

## Explícitamente fuera de alcance
- Implementar las historias de producto o alterar código de `project-app/`.
- Reintroducir evaluaciones o reportes intermedios, estancias de 800 horas o contador de horas.
- Copiar a la memoria versionada datos personales que aparezcan en ejemplos/imágenes de los formatos.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `docs/backlog/candidatos-iniciales.md`
- `docs/sprints/plan-sprints-candidato.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `docs/requerimientos/vision-inicial.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `.ai/tasks/ajuste-sprints-formatos-y-evaluaciones/**`
- `.ai/tasks/gt-hu-015-empresas-convenios/**`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md` y `.ai/state/topics/pendientes.md`
- `docs/backlog/candidatos-iniciales.md`
- `docs/sprints/plan-sprints-candidato.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`
- Los formatos de solicitud, carta de no responsiva, reporte y evaluación en `docs/formatos/`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
