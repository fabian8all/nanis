# Handoff: gt-hu-025-consultar-solicitud-propia

**Fecha:** 2026-10-14 · **Rol:** Backend (`software/backend`)

## Objetivo
Confirmar que el alumno puede consultar su propia solicitud sin exponer la de
otra persona y conservar los permisos de los demás roles.

## Archivos revisados
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-025)
- `project-app/routes/web.php`
- `project-app/app/Livewire/Student/PracticeRequests/Show.php`
- `project-app/tests/Feature/StudentPracticeRequestTest.php`
- `project-app/tests/Feature/CoordinatorPracticeRequestReviewTest.php`

## Archivos modificados
- `project-app/tests/Feature/StudentPracticeRequestTest.php`

## Decisión(es) tomada(s)
- No se cambió lógica de aplicación: el acceso existente permite la ruta al
  rol Alumno y filtra la solicitud por el ID autenticado. Las pruebas
  focalizadas ya reproducían una respuesta 200 para alumnos.
- Se añadió cobertura explícita para verificar que la página muestra la
  empresa de la solicitud propia, no la de otro alumno, y que Coordinación,
  Supervisión y Tutoría no acceden a esa ruta.

## Suposiciones vigentes
- La incidencia original corresponde a la ruta de consulta de solicitud del
  alumno; una ruta distinta con 403 requeriría su URL y flujo concretos.

## Validación
- Ejecutada: PHPUnit en el contenedor para
  `StudentPracticeRequestTest.php` y `CoordinatorPracticeRequestReviewTest.php`:
  14 tests y 108 aserciones pasaron sin advertencias.
- Pendiente: ninguna para los criterios cubiertos en esta tarea.

## Riesgos / dudas abiertas
- Si persiste un 403 en la aplicación fuera de la ruta probada, se necesita
  identificar la URL, el rol autenticado y el paso que lo produce.

## Siguiente paso recomendado
Continuar con GT-HU-027; mantener GT-HU-023 bloqueada hasta resolver sus
destinos por estado.
