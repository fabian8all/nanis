# Tarea: gt-hu-014-consulta-seleccion-empresa

**Creada:** 2026-10-10 · **Tamaño:** medium · **Owner:** frontend · **Rol:** frontend

## Objetivo
Permitir que el alumno consulte las empresas con convenio disponibles y elija
una como destino de su práctica. La elección debe poder enlazarse con la futura
solicitud de GT-HU-001, sin implementar el formulario de solicitud.

## Incluido en el alcance
- Refinar con la persona solicitante el acceso del alumno y cómo se conserva su selección.
- Mostrar solamente empresas activas, ordenadas por nombre, con nombre, dirección, teléfono, email y persona de contacto.
- Permitir elegir una empresa activa y comunicar la elegida antes de continuar al flujo de solicitud.
- Conservar el ID de la empresa en la sesión del alumno para que HU-001 lo consuma; cambiar o limpiar esa elección si la empresa deja de estar activa.
- Añadir pruebas para visibilidad de empresas activas/inactivas y selección.
- Mantener Bootstrap, Alpine y Livewire; no añadir Vite ni Tailwind.

## Explícitamente fuera de alcance
- Gestión administrativa del catálogo (GT-HU-015, ya cerrada).
- Captura y persistencia de la solicitud de prácticas (GT-HU-001).
- Configuración del IDP institucional real y administración de cuentas/roles (HU-020/HU-021).

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/routes/web.php`
- `project-app/app/Livewire/Student/Companies/**`
- `project-app/resources/views/livewire/student/companies/**`
- `project-app/resources/views/layouts/**` (solo si hace falta enlazar el catálogo)
- `project-app/resources/views/home.blade.php` (enlace contextual para alumno)
- `project-app/tests/Feature/StudentCompanyCatalogTest.php`
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md` (criterios HU-014 confirmados)
- `.ai/HANDOFF.md`
- `.ai/state/topics/resumen.md`
- `.ai/tasks/gt-hu-014-consulta-seleccion-empresa/**`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md` (GT-HU-014)
- `../../../../docs/v1.0/casos-de-uso/flujo-candidato-practica.md`
- `../../../../docs/v1.0/sprints/plan-sprints-candidato.md` (Sprint 1 y dependencias)
- `project-app/routes/web.php`
- `project-app/app/Models/Company.php`
- `project-app/app/Livewire/Companies/Index.php`

## Dependencias y decisiones abiertas
- HU-015 completada; `Company.is_active` indica disponibilidad para nuevas solicitudes.
- HU-019 completada; el simulador local autentica al alumno. Los datos del IDP real se integrarán después y no bloquean esta historia.
- La vista solo permite seleccionar a usuarios con rol `student`; Coordinación y otros roles no pueden acceder.
- La persona solicitante confirmó conservar el ID de empresa elegida en la sesión del alumno hasta que HU-001 lo consuma; no guardar una preferencia persistente en el perfil ni implementar la solicitud aquí.
- Se inspeccionó el ejemplo accesible en `/home/fabian/code/redi`: integra `aacotroneo/laravel-saml2`; `VerifyAuthSaml` admite simulación por variables de entorno, alta condicional y asigna un rol por ID numérico. No trasladar esos valores/roles sin diseñar un mapeo explícito para este proyecto.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
