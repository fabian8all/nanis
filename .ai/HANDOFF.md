# Handoff

**Fecha:** 2026-10-12 · **Rol:** software/backend · **Rama:** `develop`

## Objetivo de la sesión
Sprint 5 implementado, con historias cerradas, validadas y comprometidas por separado; integrado por fast-forward en `develop` hasta `a11bd76`.

## Estado
- HU-010 implementada y comprometida como `e40197e`; las pruebas dirigidas dieron 4 pruebas/27 aserciones y Pint pasó en 3 PHP.
- HU-012 implementada; expediente disponible para Coordinación y alumno propietario. Pruebas dirigidas: 23/212; Pint pasa. Handoff en `.ai/tasks/_closed/gt-hu-012-expediente-practicas/handoff.md`.
- HU-013 implementada, validada, cerrada y comprometida. Cuestionario versionado, escala 1–5, y respuestas consultables solo por Coordinación. Handoff en `.ai/tasks/_closed/gt-hu-013-encuesta-satisfaccion/handoff.md`.
- Migración HU-009 `2026_10_12_000015` quedó aplicada en la nueva base MySQL local. `.env` tiene una `APP_KEY` nueva; conservarla mientras la base contenga datos cifrados.

## Próximo paso
Sprint 5 está integrado y publicado en `origin/develop`; la rama
`origin/sprint/5-cierre-expediente-encuesta` quedó sincronizada. No integrar a
`main` hasta completar la validación institucional pendiente.

## Cambios ajenos que deben preservarse
No incluir ni revertir los cambios preexistentes en `.continuum/tools/continuum`, `.githooks/pre-commit`, `tools/continuum` ni archivos `Zone.Identifier`. `docs/formatos/` ahora contiene copias saneadas de los documentos fuente; revisar su `README.md` antes de usarlas. Los originales con datos personales se mantienen fuera del repositorio.

## Entorno local
Se agregó `project-app/docker-compose.override.yml` (ignorado por Git) para
evitar los puertos ocupados por otros contenedores: `nginx-proxy` compartido
sirve `nanis.net` por el puerto 80; el proxy local de diagnóstico escucha en
`8000`, el acceso directo a la app en `8001` y MySQL en `3307`. La app se une a
la red Docker externa `proxy` y se registra con `VIRTUAL_HOST=nanis.net`.
`project-app/.env` conserva credenciales aleatorias y su `APP_KEY` nueva; está
ignorado por Git. El stack está iniciado, la base quedó migrada y
`http://nanis.net/login` responde HTTP 200. No se ha creado una cuenta de
Coordinación.
