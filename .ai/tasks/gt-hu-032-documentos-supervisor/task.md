# Tarea: gt-hu-032-documentos-supervisor

**Creada:** 2026-10-14 · **Tamaño:** small · **Owner:** frontend · **Rol:** Frontend (`software/frontend`)

## Objetivo
Agrupar en la navegación del Supervisor sus documentos operativos existentes,
sin ampliar el alcance de datos ni permisos de las rutas.

## Incluido en el alcance
- Reemplazar los accesos individuales del Supervisor por el menú `Documentos`.
- Incluir Cartas pendientes, Planes de trabajo, Evaluaciones y Encuestas con
  las rutas existentes del rol Supervisor.
- Verificar los enlaces y que la navegación no se exponga a otros roles.

## Explícitamente fuera de alcance
- Cambiar rutas, consultas, autorización, datos o flujos de los módulos.
- Reorganizar la navegación de Coordinación, Alumno o Tutor.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/resources/views/layouts/app.blade.php`
- `project-app/tests/Feature/SupervisorNavigationTest.php`
- `.ai/tasks/gt-hu-032-documentos-supervisor/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-032)
- `project-app/resources/views/layouts/app.blade.php`
- `project-app/routes/web.php`
- `project-app/tests/Feature/PracticeTrackingTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
