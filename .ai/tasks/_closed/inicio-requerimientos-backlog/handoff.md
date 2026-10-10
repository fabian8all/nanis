# Handoff: inicio-requerimientos-backlog

**Fecha:** 2026-10-10 · **Rol:** gestion-proyecto

## Objetivo
Preparar y validar con la persona solicitante un backlog candidato para el prototipo de prácticas de Gestión Turística.

## Archivos revisados
- `docs/planeacion/` — Retos 4 y 5, cédula y procedimiento institucional 2025.
- `docs/requerimientos/vision-inicial.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `docs/backlog/candidatos-iniciales.md`
- `.ai/tasks/inicio-requerimientos-backlog/` y estado Continuum relacionado.

## Archivos modificados
- `docs/requerimientos/vision-inicial.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `docs/backlog/candidatos-iniciales.md`
- `.ai/state/topics/`, `.ai/tasks/inicio-requerimientos-backlog/` y `.ai/HANDOFF.md`

## Decisión(es) tomada(s)
- El prototipo se limita a prácticas operativas y de mandos medios de Gestión Turística, no estancias profesionales.
- La práctica concluye a las 240 horas e incluye una evaluación al término y un reporte final; no habrá evaluaciones ni reportes intermedios.
- El flujo base es solicitud digital → revisión de coordinación → carta de presentación → respuesta de empresa. Coordinación administra el catálogo de convenios.
- Los documentos sobre estancias (800 horas, evaluaciones/revisiones y reportes intermedios) se conservaron solo como referencia y no como requisitos del prototipo.

## Suposiciones vigentes
- Las historias del backlog son candidatas, excepto las decisiones de alcance expresamente confirmadas; faltan criterios de aceptación.
- La persona evaluadora, el instrumento y el proceso de revisión del reporte final no están definidos.

## Validación
- Ejecutada: `python3 tools/continuum doctor` — 0 problemas críticos, 1 advertencia sobre el tamaño estimado del paquete de arranque.
- No ejecutada / pendiente: priorización, estimaciones, validación de capacidad y pruebas de aplicación; no se hicieron cambios de código funcional.

## Riesgos / dudas abiertas
- El backlog aún no está aprobado con prioridades, estimaciones ni capacidad comprometida.
- Faltan detalles de roles/permisos, catálogo de empresas, cierre, evaluación final y reporte final.

## Siguiente paso recomendado
Continuar con `planificacion-sprints-y-asignacion`: organizar sprints a partir del backlog candidato y proponer la asignación de historias a roles/agentes, validando la capacidad con la persona solicitante.
