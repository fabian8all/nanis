# Handoff: cierre-expediente-roles-carta

**Fecha:** 2026-10-12 · **Rama:** `develop` · **Estado:** implementación terminada

## Resultado
- Coordinación emite la carta imprimible de término (240 horas) después de
  verificar reporte aprobado y evaluaciones terminales enviadas. La emisión
  guarda actor/fecha y cierra la práctica; el documento deja proyecto, firma y
  sello para completar fuera del sistema, sin copia firmada.
- Se añadió `PracticeTerminationLetterIssuance` y migración 000017; se retiró
  el flujo anterior de carga/revisión de carta por el alumno sin borrar su
  migración/datos históricos.
- Se aplicaron permisos del expediente completo para Coordinación, alumno y
  Tutor asignado; el Supervisor asignado solo ve su conjunto autorizado de
  documentos y su evaluación. Se restringieron descargas de anexos/aceptación.
- El Supervisor puede consultar su respuesta final inmutable; Coordinación
  conserva acceso a todos los resultados.
- Se alinearon pruebas, backlog, caso de uso, plan de sprint y memoria
  versionada.

## Validación
- Laravel: 95 pruebas/758 aserciones.
- Pint: 21 archivos PHP.
- Migración verificada indirectamente por `RefreshDatabase` en la suite SQLite.
- No se ejecutaron las pruebas Python indicadas porque el worktree no contiene
  el directorio raíz `tests/`.
- Doctor informa configuraciones no versionadas y una advertencia de tamaño
  previas a esta tarea; revisar sin incluirlas en este cambio.

## Pendiente
- Validación humana/institucional y datos de prueba quedan diferidos.
- Commit `4409f4a` publicado en `origin/develop`; la integración a `main` se
  autorizó después y está en curso en una tarea separada. No se reconstruyó el
  contenedor web.
- No incluir los cambios preexistentes en `.continuum/tools/continuum`,
  `.githooks/pre-commit` y `tools/continuum`.
