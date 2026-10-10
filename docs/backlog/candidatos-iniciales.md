# Backlog inicial candidato — prototipo de Gestión Turística

**Estado:** borrador de trabajo. El prototipo cubre prácticas operativas y de mandos medios, no estancias profesionales; concluyen a las 240 horas e incluyen una evaluación al término y un reporte final, sin evaluaciones ni reportes intermedios. Las demás historias son candidatas a validar, no compromisos aprobados.

## Objetivo de producto

Dar seguimiento a las prácticas operativas y de mandos medios de estudiantes de Gestión Turística, concentrando solicitud, documentos, plan de trabajo, evaluación única al término y reporte final.

## Orden de refinamiento sugerido

1. Confirmar el flujo y las reglas/validaciones de solicitud y cartas.
2. Definir acceso y responsabilidades de alumno, coordinación y empresa.
3. Implementar el ciclo documental y seguimiento de estados.
4. Precisar el reporte final y cómo se acredita la conclusión a las 240 horas.
5. Conciliar los documentos de estancias de 2025 con el alcance distinto de este prototipo.
6. Acordar privacidad, criterios de aceptación, prioridad y capacidad antes de planificar sprints.

El orden de refinamiento de criterios es una propuesta. Los grupos de prioridad P0/P1/P2 se aceptaron como base; no implican cadencia ni calendario aprobados.

La distribución candidata de historias en sprints y grupos de prioridad P0/P1/P2 aceptados como base está en `docs/sprints/plan-sprints-candidato.md`; aún no tiene fechas, duraciones ni estimaciones comprometidas.

## Épica GT-EP-01 — Solicitud e inicio de práctica

| ID | Historia candidata | Criterios de aceptación por acordar |
|---|---|---|
| GT-HU-001 | Como alumno, quiero llenar la solicitud de prácticas para registrar a qué empresa/puesto solicito incorporarme y las fechas de mi práctica. | Datos declarados: nombre, número de cuenta, semestre, NSS, empresa, puesto, contacto/supervisor y fechas. Coordinación revisa la solicitud; si pide cambios, el alumno corrige y reenvía. Confirmar validaciones/obligatoriedad, edición, duplicados, acuse y tratamiento del NSS. |
| GT-HU-002 | Como coordinador, quiero revisar/aprobar la solicitud y preparar la carta de presentación con sus datos precargados para que el alumno la lleve a la empresa. | Aprobación previa confirmada. Datos de carta precargados desde solicitud y modificables por coordinación. Confirmar comentarios/estados de devolución, campos que se pueden cambiar, plantilla, folio/firma, formato de descarga y registro de emisión. |
| GT-HU-003 | Como alumno, quiero consultar/descargar la carta de presentación para entregarla a la empresa seleccionada. | Definir si solo se registra su emisión/descarga o también fecha y resultado de entrega. |
| GT-HU-004 | Como empresa, quiero cargar la carta de aceptación para dejar evidencia de que recibe al alumno para su práctica. | Definir persona autorizada, formato y datos, validación de empresa/alumno, revisión institucional, reemplazo y estado cuando no es aceptado. |

**Por conciliar con la fuente 2025:** la empresa entrega la carta de aceptación al estudiante y este la lleva a coordinación; la empresa no aparece cargándola directamente. La fuente también indica aceptación verbal previa, formatos de solicitud y carta de no responsiva, y búsqueda de otra empresa tras 30 días sin aceptación.

## Épica GT-EP-02 — Plan y desarrollo de la práctica

| ID | Historia candidata | Criterios de aceptación por acordar |
|---|---|---|
| GT-HU-005 | Como alumno, quiero registrar mi plan de trabajo para documentar las actividades previstas durante la práctica. | Definir formato/campos, fecha límite, si requiere revisión o aprobación, cambios y responsable revisor. |
| GT-HU-006 | Como empresa/supervisor, quiero realizar una evaluación del alumno al término de la práctica para registrar su resultado final. | La empresa/supervisor realiza una sola evaluación al concluir las 240 horas; no hay evaluaciones intermedias. Coordinación de prácticas definirá y aprobará una rúbrica específica; acordar sus criterios, formato, captura y acceso al resultado. |
| GT-HU-007 | Como alumno, quiero cargar el reporte final de actividades para documentar lo realizado durante la práctica. | Hay un solo reporte final (sin reportes de avance). El tutor/asesor académico puede devolverlo con observaciones; el alumno lo corrige y reenvía hasta aprobación. Definir formato, estructura, evidencia/anexos, fecha límite, estados y criterios de aprobación. |
| GT-HU-012 | Como coordinador, quiero resguardar el reporte final y los documentos de cierre para mantener el expediente de la práctica. | Definir documentos de cierre, canales de entrega, permisos y validación del reporte final. Incluye la evaluación única al término; no evaluaciones ni reportes intermedios. |

## Épica GT-EP-03 — Conclusión y expediente

| ID | Historia candidata | Criterios de aceptación por acordar |
|---|---|---|
| GT-HU-008 | Como empresa, quiero emitir/entregar la carta de término para acreditar que concluyó la práctica operativa o de mandos medios. | La carta de término confirma que la práctica concluyó al completar 240 horas; será la evidencia registrada, sin contador independiente de horas. Confirmar quién prepara/firma la carta, formato y fecha efectiva de término. |
| GT-HU-009 | Como alumno, quiero cargar la carta de término para completar la evidencia documental de mi práctica. | Definir validación del archivo, revisión/aprobación, correcciones y cuándo se considera completo el expediente. |
| GT-HU-010 | Como coordinador, quiero consultar el expediente y avance de la práctica para identificar documentos y actividades pendientes. | Confirmar etapas/estados, reglas de avance, filtros, alertas y acciones disponibles; contemplar solo la evaluación y el reporte finales, sin actividades intermedias. |
| GT-HU-013 | Como coordinador, quiero recoger la satisfacción de la empresa respecto a la práctica para obtener retroalimentación del proceso. | La persona solicitante confirmó su inclusión, separada de la evaluación terminal del alumno, y que responderá el supervisor directo cuando coordinación la solicite, sin fecha fija. Solo coordinación podrá consultar las respuestas. No está definido quién diseñará/aprobará el cuestionario ni el canal. Definir preguntas y resguardo de respuestas. |

## Épica GT-EP-04 — Catálogo de empresas con convenio

| ID | Historia candidata | Criterios de aceptación por acordar |
|---|---|---|
| GT-HU-014 | Como alumno, quiero consultar las empresas con convenio vigente y seleccionar una para mi solicitud de práctica. | Mostrar solo empresas con convenio vigente para nuevas solicitudes. Si vence después de iniciar la práctica, no afectarla; si vence entre selección e inicio, solicitar revisión y autorización de coordinación antes de continuar. Acordar datos visibles. |
| GT-HU-015 | Como coordinador, quiero registrar y actualizar las empresas, sus convenios y vigencias para mantener al día el catálogo que consulta el alumnado. | La persona solicitante confirmó que coordinación administra los registros en la plataforma. Definir campos, validación de periodos e historial de cambios; revisar y autorizar solicitudes en que el convenio vence entre selección e inicio. |

La cédula/procedimiento institucional 2025 corresponden a estancias: sus 800 horas y evaluaciones/reportes intermedios quedan fuera de este prototipo, cuyo alcance confirmado es prácticas operativas y de mandos medios de 240 horas con una evaluación y un reporte final.

## Candidatos explícitamente fuera de alcance

| ID | Función candidata previa | Decisión |
|---|---|---|
| GT-HU-011 | Revisiones de avances e iteraciones de reportes intermedios. | Excluida: no habrá reportes de avance. El ciclo iterativo de corrección del reporte final corresponde a GT-HU-007 y sí está en alcance. |

## Épicas complementarias del prototipo Reto 4

Pendientes de confirmar después del flujo esencial: Datos/expediente; Normativa; Formatos; Reportes/exportación; Contacto o mensajería; filtros y búsqueda; alertas/notificaciones; navegación adaptable a móvil. El menú mostrado por el prototipo no implica que cada sección requiera una épica funcional independiente.

## Dependencias e incógnitas

- Definir usuarios/autenticación y permisos para alumno, coordinación, empresa y asesor académico. El flujo narrado no especifica todavía qué tareas tiene el asesor académico.
- Definir campos del catálogo; detallar cómo se solicita y registra la revisión/autorización cuando vence un convenio entre selección e inicio.
- Definir estados/comentarios/reenvíos de solicitud devuelta, modificaciones a los datos precargados de la carta y registro de cambios.
- Definir estados, vencimientos, notificaciones, correcciones y quién puede ver cada documento/evaluación.
- La carta de término registrada acredita las 240 horas; definir su formato, contenido y validación, sin añadir contador independiente de horas.
- Minimizar/proteger datos personales (incluido NSS) y definir acceso y retención para la evaluación final.
- Coordinación de prácticas definirá y aprobará la rúbrica de la evaluación terminal; faltan criterios, formato y reglas de acceso al resultado.
- La encuesta de satisfacción a la empresa está incluida y es distinta de la evaluación terminal del alumno; responderá el supervisor directo cuando coordinación la solicite, sin fecha fija, y solo coordinación verá las respuestas. No está definido quién diseñará/aprobará el cuestionario ni el canal; faltan preguntas y reglas de resguardo.
- Para el prototipo, el orden confirmado es solicitud digital → revisión de coordinación → carta de presentación → respuesta de empresa; la aceptación verbal previa indicada en el procedimiento 2025 no es requisito de entrada.
- Las prioridades P0/P1/P2 son una base aceptada para refinar; las estimaciones, fechas y capacidad comprometida siguen pendientes.
