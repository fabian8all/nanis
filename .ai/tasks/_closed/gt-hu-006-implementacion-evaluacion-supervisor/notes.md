# Notas de trabajo: gt-hu-006-implementacion-evaluacion-supervisor

## Criterios confirmados
- Se habilita cuando hay una aceptación empresarial aprobada y el plan HU-005 está aprobado; no espera 240 horas.
- La aplica el supervisor que cargó la aceptación aprobada.
- Una evaluación por práctica; un borrador editable hasta el único envío inmutable.
- Fija al crearla la versión publicada vigente de la rúbrica independiente `RubricType::Supervisor`.
- El alumno propietario, el supervisor evaluador y Coordinación consultan resultado y detalle.

## Decisiones técnicas
- HU-016 tiene unicidad por `practice_request_id` en `practice_evaluations`; conservar su esquema y crear tablas Supervisor separadas para ambas evaluaciones por práctica.
- Al crear, fijar `PracticeAcceptanceSubmission` aceptada y supervisor correspondiente, junto con versión publicada de rúbrica.
- Reutilizar `RubricScoreCalculator`, copiar datos del criterio a snapshots y persistir puntaje/etiqueta/aportación.
- La rúbrica Supervisor está en borrador institucional incompleto. No inventar criterios para esta HU; bloquear con error claro hasta publicación de Coordinación.
- Las claves foráneas de ambas tablas con nombres largos usan identificadores explícitos cortos para respetar el límite de 64 caracteres de MySQL.

## Validación de implementación
- `PracticeSupervisorEvaluationWorkflowTest`: 4 pruebas y 33 aserciones pasan en contenedor con SQLite en memoria.
- Las pruebas cubren bloqueo sin rúbrica publicada, fijación de versiones, borrador/envío ponderado, inmutabilidad y acceso de alumno/supervisor/Coordinación, más elegibilidad y aislamiento por supervisor.
- Migración `2026_10_11_000014` aplicada a MySQL de desarrollo; `nanis.net` responde HTTP 200.
