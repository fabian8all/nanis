# Handoff: gt-hu-010-seguimiento-practicas

**Fecha:** 2026-10-12 · **Rol:** backend · **Rama:** `sprint/5-cierre-expediente-encuesta`

## Resultado
- Implementado el seguimiento de prácticas para Coordinación, alumno, supervisor y tutor con alcance de registros por rol.
- Cada fila resume solicitud, aceptación, plan, evaluaciones terminales de Supervisor y Tutor, reporte y cierre; deriva estados y responsables desde los flujos existentes.
- Coordinación puede buscar por alumno/empresa y filtrar por etapa; las acciones se abren en sus flujos actuales, sin aprobar/devolver en la tabla.
- Los recordatorios son un resumen dinámico dentro de la plataforma con enlaces a la acción pendiente. No se almacenan como notificaciones ni se envían por correo.
- El backlog precisa ese comportamiento, incluyendo la exclusión de notificaciones persistentes.

## Validación
- `PracticeTrackingTest`: 4 pruebas y 27 aserciones aprobadas; cubre búsqueda/filtros, aislamiento de prácticas por rol, acceso denegado y pendiente de aceptación del supervisor.
- Pint `--test` aprobado para `PracticeTrackingSummary.php`, `PracticeTracking/Index.php` y `PracticeTrackingTest.php`.
- Las pruebas se ejecutaron en SQLite en memoria en un contenedor efímero. No se ejecutó la suite completa ni se desplegó la aplicación.

## Archivos principales
- Servicio de resumen: `project-app/app/Services/PracticeTrackingSummary.php`.
- Componente y vista: `project-app/app/Livewire/PracticeTracking/Index.php`, `project-app/resources/views/livewire/practice-tracking/index.blade.php`.
- Navegación y ruta: `project-app/resources/views/layouts/app.blade.php`, `project-app/routes/web.php`.
- Pruebas: `project-app/tests/Feature/PracticeTrackingTest.php`.
- Criterios/backlog: `../../../../docs/v1.0/backlog/candidatos-iniciales.md`.

## Pendiente del sprint
- Implementar HU-012 (expediente) y HU-013 (encuesta); validar Sprint 5 completo antes de integrar a `develop`.
- Aplicar al desplegar la migración de HU-009 `2026_10_12_000015`; HU-010 no agrega migraciones.
- Al recrear la aplicación, preservar el `.env` y APP_KEY del contenedor de desarrollo.
