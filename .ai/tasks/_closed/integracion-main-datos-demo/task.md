# Tarea: integracion-main-datos-demo

**Creada:** 2026-10-13 · **Tamaño:** large · **Owner:** (sin asignar) · **Rol:** (sin asignar)

## Objetivo
Integrar por fast-forward `develop` en `main` según autorización explícita.
Después, regresar a `develop` y ampliar los seeders para poblar una instalación
local con empresas, cuentas y prácticas ficticias en distintas etapas.

## Incluido en el alcance
- Actualizar referencias, revisar que `origin/main` sea ancestro de
  `origin/develop`, adelantar `main` a `develop`, publicarlo y verificar ambos
  tips; continuar el desarrollo en `develop`.
- Generar exactamente 20 empresas, una con supervisor, 1 Coordinación, 5
  Tutores/asesores y 50 alumnos, con los correos acordados.
- Crear prácticas sintéticas en varias etapas y enlazar documentos/registros
  requeridos para que las vistas por rol y seguimiento sean demostrables.
- Credenciales demo solo por configuración local del entorno, sin guardarlas
  en código o en archivos versionados; rechazar el seeding fuera de
  `local`/`testing`.
- Hacer el seeder repetible sin crear registros duplicados, documentar su
  ejecución y probar conteos, estados, relaciones y autenticación.

## Explícitamente fuera de alcance
- Sembrar datos reales o ejecutar el seeder contra producción.
- Incluir el seeder o nuevos datos demo en un merge adicional a `main` después
  de integrar la versión actual de `develop`, salvo nueva instrucción.
- Cambiar políticas académicas, formatos institucionales o datos de rúbricas
  oficiales.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/HANDOFF.md`, `.ai/state/topics/{pendientes,resumen}.md`, esta tarea,
  `.ai/tasks/integracion-main-datos-demo/{execution-plan,handoff}.md`,
  `.ai/tasks/cierre-expediente-roles-carta/{handoff,execution-plan}.md`,
  `.gitignore`.
- `project-app/database/seeders/DevelopmentDatasetSeeder.php`,
  `project-app/config/demo.php`,
  `project-app/docker-compose.yml` y `project-app/.dockerignore`,
  `project-app/app/Http/Controllers/Auth/AuthenticatedSessionController.php`,
  `project-app/tests/Feature/{DevelopmentDatasetSeederTest,CoordinatorAuthenticationTest}.php`,
  `project-app/README.md` y `project-app/.env.example`.
- `project-app/database/migrations/2026_10_12_000017_create_practice_termination_letter_issuances_table.php`
  si la validación en MySQL requiere corregir sus identificadores.
- Nuevos modelos/factories/seeder relacionados solo si son necesarios para las
  entidades dummy incluidas.

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/HANDOFF.md`
- `AI_COLLABORATION.md`
- `project-app/database/seeders/DemoDataSeeder.php`,
  `DatabaseSeeder.php`, modelos/estados de práctica y
  `project-app/tests/Feature/DemoDataSeederTest.php`.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
