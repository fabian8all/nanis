# Propuesta candidata de organización de sprints

**Estado:** la persona solicitante aprobó como base de refinamiento la secuencia de cinco sprints, el esquema de apoyo por roles y el agrupamiento de prioridades P0/P1/P2. Sin fechas, duración fija, estimaciones ni compromisos de entrega.
**Base:** `docs/backlog/candidatos-iniciales.md`.

## Capacidad y límites confirmados

- Una persona desarrolladora a tiempo completo, con apoyo de agentes IA por rol.
- La persona solicitante no quiere fijar por ahora una duración estándar de sprint. Esta propuesta agrupa trabajo por resultado, no por calendario.
- Los sprints no se estiman en horas ni fechas hasta refinar criterios y capacidad real.
- Alcance: prácticas operativas y de mandos medios, con conclusión a las 240 horas, evaluaciones terminales separadas del supervisor y del tutor, y un reporte final; sin actividades intermedias.
- Los agentes IA apoyan análisis, diseño, implementación y revisión; no se consideran personas ejecutoras adicionales ni trabajo de código paralelo. La persona desarrolladora integra y valida los cambios.

## Secuencia candidata

| Orden | Objetivo del sprint candidato | Historias | Dependencias / salida esperada |
|---|---|---|---|
| 1 | Acceso del alumno, catálogo, solicitud y carta de no responsiva | GT-HU-015, GT-HU-019, GT-HU-014, GT-HU-001, GT-HU-018 | Coordinación mantiene manualmente empresas para mostrar en el catálogo con nombre, dirección, teléfono, email y persona de contacto; la baja oculta para nuevas solicitudes y conserva referencias existentes. El alumno inicia sesión por IDP institucional (HU-019), selecciona una empresa (HU-014), registra su solicitud, acepta e imprime la carta de no responsiva para firmarla físicamente; el sistema no guarda copia firmada. Dependencias: HU-015 → HU-019 → HU-014 → HU-001; HU-018 se realiza como parte de HU-001. |
| 2 | Revisión de coordinación y carta de presentación | GT-HU-002, GT-HU-003 | Coordinación aprueba o devuelve con comentario obligatorio e historial; al aprobar genera carta oficial con folio anual `CP-AAAA-0001`, ajustable en destinatario, puesto, lugar y fechas sin alterar la solicitud; Coordinación imprime para firma física. El alumno consulta/descarga la carta en HU-003. Depende de HU-001. |
| 3 | Aceptación de empresa y plan de trabajo | GT-HU-004, GT-HU-005 | Coordinación invita al supervisor; tras verificar correo y crear contraseña, este consulta las cartas de su empresa pendientes (no solicitudes) y carga la aceptación (PDF/JPG/PNG, hasta 20 MB). Coordinación revisa cada versión, aprueba o devuelve con comentario; los archivos e historial quedan privados. Depende de HU-002/HU-003. |
| 4 | Evaluaciones finales y reporte | GT-HU-006, GT-HU-007, GT-HU-016, GT-HU-017 | Dos evaluaciones finales separadas: supervisor de empresa (HU-006) y tutor (HU-016); la evaluación del tutor incluye el reporte final, cuyo ciclo de correcciones se mantiene en HU-007. Coordinación podrá modificar ambas rúbricas mediante HU-017, conservando en cada evaluación la versión con la que fue contestada. No hay actividades intermedias. HU-016 depende de la entrega del reporte; HU-006 y HU-016 dependen de rúbricas publicadas. Faltan escalas, captura, estados y acceso. |
| 5 | Carta de término, expediente y retroalimentación | GT-HU-008, GT-HU-009, GT-HU-012, GT-HU-010, GT-HU-013 | La carta de término confirma las 240 horas y coordinación consulta/resguarda el expediente final; también se recoge la satisfacción de la empresa, separada de la evaluación terminal. No hay contador independiente en la plataforma. Depende del cierre académico; falta refinar la encuesta. |

El orden y agrupación son la base aprobada para refinar. El formato/validación de la carta de término, permisos/usuarios, tratamiento del NSS, revisión de solicitudes y gestión de archivos pueden generar criterios o dependencias adicionales antes de comprometer la secuencia.

## Asignación propuesta a roles/agentes

Los roles se toman del catálogo disponible con `tools/continuum roles list`. **La asignación es de apoyo por rol, no una delegación de trabajo independiente**; una persona desarrolladora implementa e integra.

| Sprint candidato | Rol/agente principal de apoyo | Participación y responsabilidad |
|---|---|---|
| 1 | `producto-jtbd`, `ux-research`, `ux-ui`, `backend`, `frontend` | Enmarcar el resultado para alumno/coordinación; validar necesidades con actores; diseñar y construir catálogo/solicitud y pantallas Livewire, incluyendo aceptación e impresión de la carta de no responsiva. |
| 2 | `backend`, `frontend`, `ux-ui` | Estados de revisión, correcciones, datos de carta y consulta/descarga. |
| 3 | `backend`, `frontend`, `ux-ui` | Registro de aceptación y captura del plan. |
| 4 | `backend`, `frontend`, `ux-ui` | Administración de rúbricas por coordinación; captura de las evaluaciones finales separadas del supervisor y tutor; revisión/corrección/aprobación del reporte. |
| 5 | `backend`, `frontend` | Carta de término, documentos finales, vista de expediente/avance y captura de retroalimentación de empresa. |

**Revisión transversal aprobada como base:** `gestion-proyecto` mantiene backlog y objetivos; `qa` revisa criterios y casos límite por historia; `privacidad-datos` revisa NSS y resultados de evaluación; `seguridad` revisa autorización y manejo de documentos; `accesibilidad` revisa los flujos de interfaz. Involucrar `devops-infraestructura` solo si una historia requiere despliegue o cambio de infraestructura; no hay una historia de infraestructura de producto definida ahora.

## Prioridades provisionales

- **P0 — Inicio del flujo:** catálogo, selección de empresa, solicitud y aceptación/impresión de carta de no responsiva (HU-015, HU-014, HU-001, HU-018).
- **P1 — Autorización de inicio:** revisión y carta de presentación (HU-002, HU-003), aceptación y plan (HU-004, HU-005).
- **P2 — Conclusión y expediente:** evaluaciones finales del supervisor y tutor, rúbricas configurables, reporte y documentos de cierre y encuesta de satisfacción (HU-006, HU-007, HU-008, HU-009, HU-010, HU-012, HU-013, HU-016, HU-017).

La persona solicitante aprobó usar estos grupos como base de refinamiento por dependencias del flujo; todavía no son compromisos de calendario. Las estimaciones quedan abiertas.

**Ajuste por requisito de identidad:** HU-019 (SAML2 de alumnos y alta local automática con `uCorreo` como identificador único) se añade como prerequisito de HU-014 dentro del Sprint 1 candidato. HU-020 (cuentas locales de supervisor) y HU-021 (edición de perfiles/roles por Coordinación) se mantienen como historias candidatas aún sin sprint asignado; no bloquean HU-014.

## Bloqueos para comprometer un sprint

- Definir roles, inicio de sesión y permisos de alumno, coordinación y empresa.
- Acordar campos mínimos de empresas/convenios y de la solicitud; justificar acceso y retención del NSS.
- Confirmar formato, contenido y validación de la carta de término, que acredita las 240 horas.
- Acordar criterios y escala de ambas evaluaciones finales y permisos de consulta.
- Definir qué partes de las rúbricas pueden editarse y quién publica cambios; cada evaluación debe conservar su versión aplicada.
- Definir quién diseña/aprueba el cuestionario, preguntas, canal y resguardo de la encuesta de satisfacción a empresa (HU-013). La responde el supervisor directo cuando coordinación la solicite, sin fecha fija; solo coordinación consulta las respuestas. Es separada de la evaluación terminal.
- Definir estructura, carga, estados y criterios de aprobación del reporte final y su ciclo de correcciones.
- Confirmar documentos, firma, canal de carga/entrega y reglas de expediente completo.
- Mantener la secuencia por resultados sin duración ni fechas; dimensionar historias después de refinar criterios. Reconsiderar un timebox solo si la persona solicitante lo pide.
