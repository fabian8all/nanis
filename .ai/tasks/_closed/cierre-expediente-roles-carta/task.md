# Tarea: cierre-expediente-roles-carta

**Creada:** 2026-10-12 · **Tamaño:** large · **Owner:** (sin asignar) · **Rol:** (sin asignar)

## Objetivo
Actualizar la consulta del expediente según los permisos confirmados y cambiar el
cierre para que Coordinación emita una carta imprimible de 240 horas después
del reporte y las dos evaluaciones terminales. Permitir que el supervisor
consulte su propia respuesta de encuesta.

## Incluido en el alcance
- Acceso completo a expediente para Coordinación, alumno propietario y tutor asignado.
- Expediente acotado para el supervisor asignado: carta de presentación, aceptación, reporte final y su evaluación.
- Generación/impresión por Coordinación de la carta de término con 240 horas y espacio para firma/sello receptor; su emisión registra el cierre.
- Encuesta visible solo para Coordinación y el supervisor destinatario; este último puede consultar su propia respuesta final.
- Pruebas de permisos, cierre, emisión, impresión y aislamiento de respuestas.
- Actualizar la memoria Continuum con decisiones, pruebas y lo que siga pendiente.

## Explícitamente fuera de alcance
- Crear usuarios o datos de prueba en la base local; la persona solicitante quiere hacerlo después de terminar el resto.
- Publicar rúbricas institucionales: se conservarán las rúbricas de demostración y Coordinación las gestionará desde el módulo existente.
- Integrar `develop` a `main` antes de una posterior decisión de aceptación.
- Versionar el PDF de referencia agregado fuera del worktree de `develop`; se usa solo para reconocer el formato.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `.ai/HANDOFF.md`, `.ai/state/topics/{resumen,pendientes}.md`, esta tarea y su `execution-plan.md`/`handoff.md`.
- `project-app/app/{Models,Services,Livewire,Http/Controllers,Enums}/` en los componentes de expediente, emisión de carta, evaluación y encuesta descritos en el plan.
- `project-app/database/migrations/2026_10_12_000017_create_practice_termination_letter_issuances_table.php`.
- `project-app/routes/web.php`, `project-app/resources/views/layouts/app.blade.php` y vistas de expediente, carta imprimible, bandeja de Coordinación y encuesta.
- Pruebas existentes de expediente, seguimiento, encuesta y carga/revisión de carta de término; nuevas pruebas de emisión/impresión.
- `../../../../docs/v1.0/backlog/candidatos-iniciales.md`, `../../../../docs/v1.0/sprints/plan-sprints-candidato.md` y `../../../../docs/v1.0/casos-de-uso/flujo-candidato-practica.md` para mantener la fuente de requisitos actualizada.

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/tasks/_closed/gt-hu-012-expediente-practicas/handoff.md`
- `.ai/tasks/_closed/gt-hu-013-encuesta-satisfaccion/handoff.md`
- `.ai/tasks/_closed/gt-hu-008-carta-termino/handoff.md`
- `.ai/tasks/_closed/gt-hu-009-carga-carta-termino/handoff.md`
- `../../../../docs/v1.0/formatos/README.md` y el PDF local de referencia (no versionarlo).
- `PracticeRecords/Show`, `PracticeTrackingSummary`, controladores de archivos, rutas y pruebas correspondientes.

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |

## Decisiones confirmadas
- La carta de término es una vista imprimible generada en la plataforma; Coordinación la emite/entrega tras verificar reporte final aprobado y evaluaciones de Tutor y Supervisor enviadas.
- Se conservan las 240 horas acordadas para el prototipo. El formato de referencia dice 400 horas, pero solo aporta la estructura; la carta muestra espacio para firma y sello de la unidad receptora. No se sube una copia firmada.
- Emitir la carta completa el expediente y cambia la práctica a `completed`.
- Acceso completo: Coordinación, alumno propietario y tutor asignado. El supervisor asignado consulta solo carta de presentación, su aceptación, reporte aprobado y su propia evaluación.
- El supervisor puede consultar su encuesta y respuesta final; no puede consultar respuestas ajenas. Coordinación ve todos los resultados. Alumno y Tutor no tienen acceso.
- Las rúbricas existentes y sus datos demo se conservan; el módulo `/coordinacion/rubricas` ya permite a Coordinación editar y publicar versiones.
