# Tarea: gt-hu-053-verificar-simulador-saml

**Creada:** 2026-10-16 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** DevOps / Infraestructura (`software/devops-infraestructura`)

## Objetivo
Verificar y documentar la configuración existente del usuario de prueba del
simulador SAML mediante `SAML_SIMULATOR_EMAIL`. No se implementarán cambios de
código; no se leerán ni versionarán valores locales del `.env`.

## Incluido en el alcance
- Confirmar que el contenedor monta el `.env` local y que el simulador consulta
  `SAML_SIMULATOR_EMAIL` sin leer el valor secreto/local.
- Documentar el nombre de la variable, dónde configurarla y que la edición del
  `.env` montado se refleja sin reconstruir el contenedor, si se verifica.
- Registrar la evidencia y el resultado en el handoff de la tarea.

## Explícitamente fuera de alcance
- Cambiar código, autenticación, identidad institucional o valores locales de
  credenciales.
- Agregar `.env` o valores reales de configuración al índice de Git.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/tasks/gt-hu-053-verificar-simulador-saml/task.md`
- `.ai/tasks/gt-hu-053-verificar-simulador-saml/execution-plan.md`
- `.ai/tasks/gt-hu-053-verificar-simulador-saml/notes.md`
- `.ai/tasks/gt-hu-053-verificar-simulador-saml/handoff.md`
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.2/backlog/backlog-candidato.md` (GT-HU-053)
- `project-app/docker-compose.yml`
- `project-app/app/Http/Controllers/Auth/SamlSessionController.php`
- `.gitignore` y `project-app/.gitignore` (ignorado de configuración local)

## Criterios de aceptación
- Se verifica el montaje/configuración sin imprimir ni leer valores locales del
  `.env`.
- La documentación indica `SAML_SIMULATOR_EMAIL` como configuración local sin
  incluir correos ni credenciales.
- No se generan cambios de aplicación.
- El handoff deja evidencia suficiente y se solicita aprobación de cierre antes
  de crear el commit.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
