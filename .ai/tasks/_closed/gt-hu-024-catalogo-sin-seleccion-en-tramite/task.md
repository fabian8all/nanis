# Tarea: gt-hu-024-catalogo-sin-seleccion-en-tramite

**Creada:** 2026-10-14 · **Tamaño:** medium · **Owner:** backend · **Rol:** Backend (`software/backend`)

## Objetivo
Permitir que el alumno consulte el catálogo sin poder iniciar otra selección
mientras su solicitud esté en trámite. Solo una solicitud devuelta permite
seleccionar una empresa distinta; el servidor debe impedir selecciones
directas no autorizadas, no solo ocultar controles en la interfaz.

## Incluido en el alcance
- Mantener accesible el catálogo desde la navegación del alumno.
- Mostrar las empresas activas y conservar la consulta del catálogo durante
  una solicitud existente.
- Aplicar en la interfaz y en los componentes Livewire la regla acordada para
  bloquear o permitir la selección y el reenvío de solicitud.
- Permitir selección cuando no existe solicitud o la solicitud vigente está
  devuelta; bloquearla si está pendiente, aprobada o completada.
- Al volver a seleccionar con una solicitud devuelta, actualizar esa misma
  solicitud al reenviarla, sin crear una segunda.
- Conservar la corrección de una solicitud devuelta y las validaciones de
  empresa activa.
- Cubrir por pruebas la matriz de estados acordada, los intentos directos y el
  aislamiento por rol.

## Explícitamente fuera de alcance
- Permitir selección en estados pendiente, aprobado o completado.
- Cambiar la autenticación, la consulta de solicitudes de otros alumnos o el
  mantenimiento del catálogo de Coordinación.
- Cambiar las reglas generales de destino implementadas por GT-HU-023.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/resources/views/layouts/app.blade.php`
- `project-app/app/Livewire/Student/Companies/Index.php`
- `project-app/resources/views/livewire/student/companies/index.blade.php`
- `project-app/app/Livewire/Student/PracticeRequests/Show.php`
- `project-app/tests/Feature/StudentCompanyCatalogTest.php`
- `project-app/tests/Feature/StudentPracticeRequestTest.php`
- `docs/v1.1/backlog/backlog-candidato.md`
- `.ai/tasks/gt-hu-024-catalogo-sin-seleccion-en-tramite/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-024)
- `docs/v1.1/sprints/plan-sprints-candidato.md`
- `project-app/app/Livewire/Student/Companies/Index.php`
- `project-app/app/Livewire/Student/PracticeRequests/Show.php`
- `project-app/tests/Feature/StudentCompanyCatalogTest.php`
- `project-app/tests/Feature/StudentPracticeRequestTest.php`

## Refinamiento pendiente
**Resuelto por la persona solicitante:** solo la ausencia de solicitud o el
estado `Devuelta` permiten seleccionar empresa. `Pendiente`, `Aprobada` y
`Completada` bloquean la selección. La selección en una solicitud devuelta
actualiza esa misma solicitud al reenviarla.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
