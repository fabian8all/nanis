# Propuesta candidata de organización de sprints

**Estado:** la persona solicitante aprobó como base de refinamiento la secuencia de cinco sprints, el esquema de apoyo por roles y el agrupamiento de prioridades P0/P1/P2. Sin fechas, duración fija, estimaciones ni compromisos de entrega.
**Base:** `docs/backlog/candidatos-iniciales.md`.

## Capacidad y límites confirmados

- Una persona desarrolladora a tiempo completo, con apoyo de agentes IA por rol.
- La persona solicitante no quiere fijar por ahora una duración estándar de sprint. Esta propuesta agrupa trabajo por resultado, no por calendario.
- Los sprints no se estiman en horas ni fechas hasta refinar criterios y capacidad real.
- Alcance: prácticas operativas y de mandos medios, con conclusión a las 240 horas, una evaluación al término y un reporte final; sin evaluaciones ni reportes intermedios.
- Los agentes IA apoyan análisis, diseño, implementación y revisión; no se consideran personas ejecutoras adicionales ni trabajo de código paralelo. La persona desarrolladora integra y valida los cambios.

## Secuencia candidata

| Orden | Objetivo del sprint candidato | Historias | Dependencias / salida esperada |
|---|---|---|---|
| 1 | Catálogo y solicitud de práctica | GT-HU-015, GT-HU-014, GT-HU-001 | Coordinación mantiene convenios; el alumno puede seleccionar empresa vigente y enviar solicitud. HU-014 depende de HU-015; HU-001 depende del catálogo. |
| 2 | Revisión de coordinación y carta de presentación | GT-HU-002, GT-HU-003 | Coordinación revisa, devuelve para corrección o aprueba; el alumno consulta/descarga la carta. Depende de HU-001. |
| 3 | Aceptación de empresa y plan de trabajo | GT-HU-004, GT-HU-005 | Se registra aceptación y el alumno prepara el plan. Depende de la carta y de la selección aprobada. |
| 4 | Cierre académico | GT-HU-006, GT-HU-007 | Una evaluación de empresa/supervisor al término con rúbrica específica por definir y un reporte final que el tutor/asesor académico devuelve para corrección hasta aprobarlo. No hay reportes de avance. Su tamaño no puede estimarse hasta acordar rúbrica y criterios/estados de revisión. |
| 5 | Carta de término, expediente y retroalimentación | GT-HU-008, GT-HU-009, GT-HU-012, GT-HU-010, GT-HU-013 | La carta de término confirma las 240 horas y coordinación consulta/resguarda el expediente final; también se recoge la satisfacción de la empresa, separada de la evaluación terminal. No hay contador independiente en la plataforma. Depende del cierre académico; falta refinar la encuesta. |

El orden y agrupación son la base aprobada para refinar. El formato/validación de la carta de término, permisos/usuarios, tratamiento del NSS, revisión de solicitudes y gestión de archivos pueden generar criterios o dependencias adicionales antes de comprometer la secuencia.

## Asignación propuesta a roles/agentes

Los roles se toman del catálogo disponible con `tools/continuum roles list`. **La asignación es de apoyo por rol, no una delegación de trabajo independiente**; una persona desarrolladora implementa e integra.

| Sprint candidato | Rol/agente principal de apoyo | Participación y responsabilidad |
|---|---|---|
| 1 | `producto-jtbd`, `ux-research`, `ux-ui`, `backend`, `frontend` | Enmarcar el resultado para alumno/coordinación; validar necesidades con actores; diseñar y construir catálogo/solicitud y pantallas Livewire. |
| 2 | `backend`, `frontend`, `ux-ui` | Estados de revisión, correcciones, datos de carta y consulta/descarga. |
| 3 | `backend`, `frontend`, `ux-ui` | Registro de aceptación y captura del plan. |
| 4 | `backend`, `frontend` | Registro de evaluación terminal aplicada por empresa/supervisor y flujo iterativo de revisión/corrección/aprobación del reporte final por tutor/asesor académico; requiere criterios antes de implementar. |
| 5 | `backend`, `frontend` | Carta de término, documentos finales, vista de expediente/avance y captura de retroalimentación de empresa. |

**Revisión transversal aprobada como base:** `gestion-proyecto` mantiene backlog y objetivos; `qa` revisa criterios y casos límite por historia; `privacidad-datos` revisa NSS y resultados de evaluación; `seguridad` revisa autorización y manejo de documentos; `accesibilidad` revisa los flujos de interfaz. Involucrar `devops-infraestructura` solo si una historia requiere despliegue o cambio de infraestructura; no hay una historia de infraestructura de producto definida ahora.

## Prioridades provisionales

- **P0 — Inicio del flujo:** catálogo, selección de empresa y solicitud (HU-015, HU-014, HU-001).
- **P1 — Autorización de inicio:** revisión y carta de presentación (HU-002, HU-003), aceptación y plan (HU-004, HU-005).
- **P2 — Conclusión y expediente:** evaluación/reporte finales, documentos de cierre y encuesta de satisfacción (HU-006, HU-007, HU-008, HU-009, HU-010, HU-012, HU-013).

La persona solicitante aprobó usar estos grupos como base de refinamiento por dependencias del flujo; todavía no son compromisos de calendario. Las estimaciones quedan abiertas.

## Bloqueos para comprometer un sprint

- Definir roles, inicio de sesión y permisos de alumno, coordinación y empresa.
- Acordar campos mínimos de empresas/convenios y de la solicitud; justificar acceso y retención del NSS.
- Confirmar formato, contenido y validación de la carta de término, que acredita las 240 horas.
- Diseñar/aprobar la rúbrica específica de la evaluación única, sus criterios y quién puede consultar resultados.
- Definir quién diseña/aprueba el cuestionario, preguntas, canal y resguardo de la encuesta de satisfacción a empresa (HU-013). La responde el supervisor directo cuando coordinación la solicite, sin fecha fija; solo coordinación consulta las respuestas. Es separada de la evaluación terminal.
- Definir estructura, carga, estados y criterios de aprobación del reporte final y su ciclo de correcciones.
- Confirmar documentos, firma, canal de carga/entrega y reglas de expediente completo.
- Mantener la secuencia por resultados sin duración ni fechas; dimensionar historias después de refinar criterios. Reconsiderar un timebox solo si la persona solicitante lo pide.
