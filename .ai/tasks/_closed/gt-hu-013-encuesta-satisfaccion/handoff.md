# Handoff: gt-hu-013-encuesta-satisfaccion

**Fecha:** 2026-10-12 · **Rol:** software/backend

## Objetivo
Recopilar retroalimentación autenticada del supervisor directo sobre la
experiencia de práctica, con cuestionario institucional versionado y respuesta
privada para Coordinación.

## Archivos modificados
- Migración `2026_10_12_000016_create_practice_satisfaction_surveys_tables.php`.
- Modelos/enums de versiones, preguntas, solicitudes y respuestas; relación
  desde `PracticeRequest`.
- Vistas Livewire de Coordinación (publicación, solicitud, consulta y
  cancelación) y Supervisor (pendientes y envío).
- Rutas y navegación por rol.
- `PracticeSatisfactionSurveyTest.php` cubre escala, versionado, destinatario,
  permisos, respuesta única e inmutable, cancelación y requisitos de envío.
- Backlog candidato registra escala 1–5 y comportamiento implementado.

## Decisiones
- La escala es fija, entera, de 1 a 5; comentario opcional por pregunta.
- Las versiones publicadas y sus preguntas son inmutables. Cada solicitud fija
  la versión publicada vigente al momento de creación.
- Una práctica permite una solicitud; Coordinación puede cancelar una solicitud
  pendiente, que queda terminal y no se reabre.
- Solo responde el supervisor autenticado de la aceptación vigente. La respuesta
  final es única e inmutable; solo Coordinación la consulta.
- HU-013 no bloquea ni altera los cuatro criterios de cierre de HU-012.

## Validación
- Suite Laravel completa: 100 pruebas y 748 aserciones aprobadas.
- HU-013 dirigida: 5 pruebas/62 aserciones; Pint pasa en los PHP modificados.
- Migración 000016 aplicada en MySQL local; `http://nanis.net/login` responde
  HTTP 200.

## Riesgos / pendientes
- La validación institucional del cuestionario, resguardo y permisos sigue
  pendiente.
- El clone solo tiene la rama remota `sprint/5-cierre-expediente-encuesta`; no
  existen refs locales/remotas de `develop` o `main` para integrar el sprint.

## Siguiente paso
La tarea quedó cerrada y HU-013 comprometida con el slug de tarea en la rama
del Sprint 5. Coordinar la integración a `develop` cuando exista la rama de
destino.
