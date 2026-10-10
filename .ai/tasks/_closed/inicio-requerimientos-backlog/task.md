# Tarea: inicio-requerimientos-backlog

**Creada:** 2026-10-10 · **Tamaño:** large · **Owner:** gestion-proyecto · **Rol:** Gestión de proyecto (ágil/Scrum) (`comun/gestion-proyecto`)

## Objetivo
Construir y validar con la persona solicitante el backlog de un prototipo web para gestionar prácticas profesionales de la Licenciatura en Gestión Turística, usando los documentos de planeación como base sin tratar sus propuestas como requisitos aprobados.

## Incluido en el alcance
- Preparar el espacio documental del proyecto (`docs/`).
- Facilitar la obtención y validación de requisitos con la persona solicitante.
- Documentar casos de uso, criterios de aceptación y restricciones.
- Refinar y priorizar el backlog y proponer sprints/plan de trabajo cuando se conozcan el alcance y la capacidad.
- Preparar la base técnica solicitada en `project-app/` y organizar la documentación en `docs/`.
- Mantener explícitas las decisiones pendientes y supuestos; no inventar el dominio de negocio.
- Limitar el prototipo inicial a Gestión Turística.

## Explícitamente fuera de alcance
- Implementar funcionalidades de negocio antes de validar requisitos.
- Seleccionar reglas de negocio o perfiles de usuario sin evidencia de la persona solicitante.
- Extender el primer prototipo a otros programas académicos.
- Cambiar la arquitectura acordada: Laravel + Livewire, Alpine.js, Bootstrap y MySQL; sin Vite ni Tailwind.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `docs/**`
- `project-app/**`
- `.ai/HANDOFF.md`
- `.ai/tasks/inicio-requerimientos-backlog/**`
- `.ai/state/topics/pendientes.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/estado-dev.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/state/topics/resumen.md`
- `.ai/state/topics/pendientes.md`
- `AI_COLLABORATION.md`
- `docs/README.md`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |

## Criterio de terminado
- Requisitos priorizados y verificables, validados con la persona solicitante.
- Casos de uso y criterios de aceptación enlazados con los requisitos.
- Backlog priorizado, con dependencias y estimaciones iniciales, aprobado para el alcance del prototipo.
- Plan de trabajo/sprints propuesto, distinguiendo decisiones confirmadas y pendientes.
