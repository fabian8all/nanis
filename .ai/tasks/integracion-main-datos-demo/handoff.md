# Handoff: integracion-main-datos-demo

**Fecha:** 2026-10-13 · **Rama:** `develop` · **Estado:** implementación y
validación completadas; pendiente publicar los cambios de esta tarea.

## Resultado
- `develop` se integró por fast-forward en `main` hasta `6bce8e9`; la tarea de
  datos continúa solamente en `develop`.
- Se añadió `DevelopmentDatasetSeeder`, repetible y protegido para entornos
  `local`/`testing`: 20 empresas, 5 asesores, 1 supervisor, 50 alumnos y 50
  prácticas repartidas desde solicitud pendiente/devuelta hasta reporte
  aprobado. Las cuentas demo usan una contraseña hashada definida solo por
  `.env`; el acceso por contraseña de alumnos sigue bloqueado fuera de esos
  entornos.
- Los correos del coordinador, supervisor y dominio institucional se
  configuran en `.env`. `.env` y `docker-compose.override.yml` están ignorados;
  los valores exactos solicitados no se incluyeron en archivos versionados.
- Se corrigió la migración 000017 con nombres breves para las restricciones
  MySQL. La app persiste `storage` en un volumen Docker y excluye ese contenido
  local del contexto de build.
- La base `nanis_local` ya está migrada y sembrada. Se conservaron los datos
  preexistentes: había una cuenta coordinadora distinta, así que el total es
  2 coordinadores (1 existente y 1 demo), además de las 57 cuentas demo.

## Validación
- Suite Laravel: **99 pruebas, 802 aserciones**.
- Pint: **6 archivos PHP** validados.
- Migración y segunda ejecución del seeder completadas contra MySQL 8.4; los
  conteos se mantienen en 20 empresas, 50 solicitudes, 25 aceptaciones, 20
  planes y 10 reportes.
- `http://nanis.net/` respondió **200**. Los 25 PDF ficticios de aceptación
  existen en el volumen privado persistente.

## Precauciones y siguiente paso
- No integrar los cambios nuevos a `main` sin autorización adicional.
- Preservar las modificaciones preexistentes en `.continuum/tools/continuum`,
  `.githooks/pre-commit` y `tools/continuum`.
- Publicar solo los archivos de esta tarea en `develop`; no incluir `.env`,
  `docker-compose.override.yml` ni cambios ajenos.
