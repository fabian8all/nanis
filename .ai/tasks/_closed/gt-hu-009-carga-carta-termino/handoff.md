# Handoff: gt-hu-009-carga-carta-termino

**Fecha:** 2026-10-12 · **Rol:** backend

## Objetivo
Permitir al alumno cargar carta de término y a Coordinación revisarla, aceptarla o devolverla para corrección.

## Archivos modificados
- Modelo, enum, relación y migración `2026_10_12_000015_create_practice_termination_letters_table.php`.
- Componentes Livewire de carga del alumno y bandeja/revisión de Coordinación; controlador de descarga privada y vistas/rutas.
- Pruebas `StudentPracticeTerminationLetterTest` y `CoordinatorPracticeTerminationLetterTest`.
- Backlog HU-009; continuidad en `.ai/HANDOFF.md` y `.ai/state/topics/resumen.md`.

## Resultado
- Un registro vigente por práctica; estados pendiente de revisión, devuelta, aceptada.
- Carga PDF/JPG/PNG hasta 20 MB en disco privado; la aceptación requiere que Coordinación confirme haber revisado los campos de HU-008.
- Devolución requiere comentario. El alumno corrige y reemplaza el archivo; el archivo previo se retira y no se conserva historial.
- Solo alumno propietario y Coordinación pueden acceder/descargar; Tutor y Supervisor no acceden.

## Validación
- SQLite en memoria: 9 pruebas y 70 aserciones aprobadas.
- Pint PASS en los 11 archivos PHP modificados.
- Migración 000015 no se aplicó a la base local; no se alteraron servicios/DB locales.

## Pendientes / riesgos
- No se ejecuta extracción automática del contenido de carta ni cálculo de horas; Coordinación valida manualmente.
- El expediente completo y su cierre se implementan en HU-012; tablero/alertas en HU-010.
- Probar migración/aplicación desplegada después de completar el sprint, conservando el `.env` runtime.

## Siguiente paso
Implementar HU-010 como vista agregada del flujo, estados/pendientes y notificaciones dentro de plataforma.
