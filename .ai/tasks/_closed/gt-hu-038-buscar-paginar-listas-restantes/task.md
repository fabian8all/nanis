# Tarea: gt-hu-038-buscar-paginar-listas-restantes

**Creada:** 2026-10-14 · **Tamaño:** large · **Owner:** backend (reclamado 2026-10-14)

## Objetivo
Permitir buscar y paginar las listas de negocio que carezcan de cualquiera de
esos controles, sin duplicar controles existentes ni ampliar el acceso a
registros. El alcance incluye las vistas principales, historiales de versiones,
listas administrativas de rúbricas y opciones de selección.

## Incluido en el alcance
- Inventariar listas visibles por rol y clasificar por búsqueda, paginación,
  campos visibles, filtros y límites de autorización existentes.
- Incorporar controles solo en listas que carezcan de búsqueda o paginación;
  las que ya tienen ambos conservan sus controles sin duplicados.
- Definir campos de búsqueda pertinentes por lista, conservar filtros y
  alcance por rol, reiniciar a la primera página al cambiar búsqueda/filtros y
  mostrar estados vacíos informativos.
- Cubrir bandejas/listas principales, historiales de versiones, administración
  de rúbricas y opciones de selección que representen registros de negocio.
- Añadir pruebas para filtros por rol, reinicio de página, resultados vacíos y
  ausencia de duplicados.

## Explícitamente fuera de alcance
- Cambios a autenticación, autorizaciones, modelos, estados o asociaciones.
- Controles duplicados en listas con búsqueda y paginación funcionales.
- Inventarios o formularios que no sean listas de registros de negocio.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- **Inventario/documentación de la tarea**
- `.ai/HANDOFF.md`
- `.ai/state/topics/pendientes.md`
- `.ai/tasks/gt-hu-038-buscar-paginar-listas-restantes/task.md`
- `.ai/tasks/gt-hu-038-buscar-paginar-listas-restantes/execution-plan.md`
- `.ai/tasks/gt-hu-038-buscar-paginar-listas-restantes/handoff.md`
- **Componentes Livewire y vistas**
  - `project-app/app/Livewire/Coordinator/PracticeRequests/Index.php`
  - `project-app/app/Livewire/Coordinator/PracticeAcceptances/Index.php`
  - `project-app/app/Livewire/Coordinator/PracticePlans/Index.php`
  - `project-app/app/Livewire/Coordinator/PracticeReports/Index.php`
  - `project-app/app/Livewire/Coordinator/PracticeEvaluations/Index.php`
  - `project-app/app/Livewire/Coordinator/PracticeSupervisorEvaluations/Index.php`
  - `project-app/app/Livewire/Coordinator/PracticeTerminationLetters/Index.php`
  - `project-app/app/Livewire/Coordinator/Rubrics/Manage.php`
  - `project-app/app/Livewire/Coordinator/PracticeSurveys/Manage.php`
  - `project-app/app/Livewire/Supervisor/PracticeLetters/Index.php`
  - `project-app/app/Livewire/Supervisor/PracticePlans/Index.php`
  - `project-app/app/Livewire/Supervisor/PracticeEvaluations/Index.php`
  - `project-app/app/Livewire/Supervisor/PracticeSurveys/Index.php`
  - `project-app/app/Livewire/Tutor/PracticeReports/Index.php`
  - `project-app/app/Livewire/Tutor/PracticeEvaluations/Index.php`
  - `project-app/app/Livewire/Student/Companies/Index.php`
  - `project-app/resources/views/livewire/coordinator/practice-requests/index.blade.php`
  - `project-app/resources/views/livewire/coordinator/practice-acceptances/index.blade.php`
  - `project-app/resources/views/livewire/coordinator/practice-plans/index.blade.php`
  - `project-app/resources/views/livewire/coordinator/practice-reports/index.blade.php`
  - `project-app/resources/views/livewire/coordinator/practice-evaluations/index.blade.php`
  - `project-app/resources/views/livewire/coordinator/practice-supervisor-evaluations/index.blade.php`
  - `project-app/resources/views/livewire/coordinator/practice-termination-letters/index.blade.php`
  - `project-app/resources/views/livewire/coordinator/rubrics/manage.blade.php`
  - `project-app/resources/views/livewire/coordinator/practice-surveys/manage.blade.php`
  - `project-app/resources/views/livewire/supervisor/practice-letters/index.blade.php`
  - `project-app/resources/views/livewire/supervisor/practice-plans/index.blade.php`
  - `project-app/resources/views/livewire/supervisor/practice-evaluations/index.blade.php`
  - `project-app/resources/views/livewire/supervisor/practice-surveys/index.blade.php`
  - `project-app/resources/views/livewire/tutor/practice-reports/index.blade.php`
  - `project-app/resources/views/livewire/tutor/practice-evaluations/index.blade.php`
  - `project-app/resources/views/livewire/student/companies/index.blade.php`
- **Pruebas**
  - `project-app/tests/Feature/CoordinatorPracticeRequestReviewTest.php`
  - `project-app/tests/Feature/CoordinatorPracticeAcceptanceTest.php`
  - `project-app/tests/Feature/PracticePlanWorkflowTest.php`
  - `project-app/tests/Feature/PracticeReportWorkflowTest.php`
  - `project-app/tests/Feature/PracticeEvaluationWorkflowTest.php`
  - `project-app/tests/Feature/PracticeSupervisorEvaluationWorkflowTest.php`
  - `project-app/tests/Feature/PracticeTerminationLetterIssuanceTest.php`
  - `project-app/tests/Feature/CoordinatorRubricManagementTest.php`
  - `project-app/tests/Feature/PracticeSatisfactionSurveyTest.php`
  - `project-app/tests/Feature/StudentCompanyCatalogTest.php`
  - Si se necesita cobertura transversal adicional, agregar un archivo de
    pruebas dentro de `project-app/tests/Feature/`.

## Inventario y decisión de alcance (2026-10-15)

Las listas se identificaron siguiendo las rutas autenticadas por rol en
`project-app/routes/web.php`, sus componentes Livewire y las vistas asociadas.
La búsqueda compara texto sin distinguir mayúsculas con los campos indicados;
la paginación será de 15 registros. Búsqueda y paginación se harán sobre la
consulta ya limitada por rol/estado, y cambiar el texto restablecerá la página.

| Rol / vista | Registros y campos visibles pertinentes | Estado previo | Cambio |
|---|---|---|---|
| Coordinación — solicitudes | Solicitudes pendientes; alumno/nombre/correo, empresa, grado-grupo, fechas | Sin búsqueda ni paginación | Agregar ambas |
| Coordinación — aceptaciones | Envíos pendientes e historial revisado; folio, alumno, empresa, supervisor/nombre/correo, versión, estado/fecha y comentario | Sin controles en ambas listas | Agregar búsqueda y paginación independientes |
| Coordinación — planes | Versiones de plan; alumno, empresa, versión y estado | Sin controles | Agregar ambas |
| Coordinación — reportes y opciones de tutor | Prácticas elegibles; alumno, empresa, tutor; versiones de cada práctica; opciones de tutor por nombre/correo | Sin controles | Buscar/paginar prácticas elegibles y opciones de tutor, con filtros independientes |
| Coordinación — evaluaciones de tutor/supervisor | Evaluaciones enviadas; alumno, empresa, evaluador y calificación | Sin controles | Agregar ambas en cada bandeja |
| Coordinación — cartas de término | Prácticas elegibles; alumno, empresa y periodo | Ya pagina, no busca | Agregar búsqueda sin duplicar paginación |
| Coordinación — rúbricas | Versiones por tipo; tipo de rúbrica, número de versión y estado | Historial anidado sin controles; catálogo fijo de dos rúbricas | Buscar/paginar versiones por rúbrica |
| Coordinación — encuestas | Versiones de cuestionario; prácticas elegibles (alumno/empresa/supervisor); solicitudes y respuestas (alumno/empresa/supervisor/estado/versión) | Tres colecciones sin controles | Buscar/paginar cada colección de forma independiente |
| Supervisor — cartas | Cartas de su empresa; folio y alumno; el detalle de cada carta conserva envíos/versiones | Sin controles | Agregar búsqueda y paginación a su bandeja |
| Supervisor — planes | Versiones; alumno, versión y estado | Sin controles | Agregar ambas |
| Supervisor — evaluaciones | Prácticas asignadas; alumno, empresa y versión del plan aprobado | Sin controles | Agregar ambas |
| Supervisor — encuestas | Solicitudes/respuestas propias; alumno, empresa, fecha y estado | Sin controles | Agregar ambas |
| Tutor — reportes | Versiones asignadas; alumno, empresa, versión y estado | Sin controles | Agregar ambas |
| Tutor — evaluaciones | Prácticas asignadas; alumno, empresa, versión aprobada y estado de evaluación | Sin controles | Agregar ambas |
| Alumno — catálogo de empresas | Opciones activas; nombre, dirección, teléfono, correo y contacto | Lista sin controles | Agregar búsqueda y paginación conservando selección en sesión |
| Todos los roles — Seguimiento | Solicitudes visibles dentro del alcance del rol; alumno/empresa y filtros de etapa/estado | Ya busca, filtra y pagina | Sin cambios |
| Coordinación — catálogo de empresas | Empresas; búsqueda por nombre y paginación de 20, con filtro de activas solo al buscar | Ya tiene ambos controles (GT-HU-030) | Sin cambios |

Los historiales asociados a una sola práctica o expediente en vistas de detalle
se conservan completos como contexto de auditoría; el inventario cubre las
bandejas de versiones/envíos que reúnen registros de múltiples prácticas. Los
formularios de criterios de rúbrica y preguntas de encuesta son editores, no
listas de consulta de registros; los selectores de tutor siguen siendo opciones
de asignación y no una bandeja de registros.

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-038 y refinamiento)
- `docs/v1.1/sprints/plan-sprints-candidato.md` (Sprint 5)
- Componentes, vistas y pruebas identificados por el inventario de listas

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
