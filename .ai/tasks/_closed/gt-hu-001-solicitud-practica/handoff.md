# Handoff: gt-hu-001-solicitud-practica

## Estado

HU-001 completada en `sprint/1-catalogo-solicitud`; tarea asignada a `backend`.
Implementa la solicitud del alumno, validaciones y aceptación/impresión de la
carta de no responsiva.

## Decisiones confirmadas

- Campos obligatorios: correo UCOL, grado/grupo, fecha de inicio, lugar de
  práctica, destinatario de la carta, puesto y fecha de término.
- El ID de empresa procede de `practice.selected_company_id` y se valida de
  nuevo al enviar; una empresa inactiva bloquea el envío.
- La fecha de término debe ser posterior a la fecha de inicio.
- Se impide una solicitud abierta por alumno. Una enviada se mantiene de solo
  lectura hasta que Coordinación la devuelva; el alumno corrige y reenvía la
  misma solicitud.
- La confirmación de envío es sencilla, sin folio.
- La carta fuente tiene espacios para llenado manual. Se conserva su texto en la
  vista imprimible, cuya fecha se actualiza al día de impresión. El alumno la
  completa y firma físicamente; no se almacena copia firmada.

## Implementación

- `practice_requests` guarda campos de solicitud, estado y fecha de aceptación
  de la no responsiva. No se añadió NSS.
- `PracticeRequestStatus` contiene `pending`, `returned`, `approved` y
  `completed`, para distinguir solicitud abierta, devolución y cierre futuro.
- `/alumno/solicitud` protege el acceso por rol estudiante; el catálogo enlaza
  con el formulario.
- La comprobación de solicitud duplicada serializa envíos por fila de alumno.
- La tarea no implementa revisión/devolución por Coordinación, que corresponde
  a GT-HU-002.

## Validación

- `StudentPracticeRequestTest`: 7 pruebas y 46 aserciones aprobadas.
- Pint: archivos PHP de HU-001 aprobados.
- `/alumno/solicitud` sin sesión redirige a login (HTTP 302).
- La suite completa pasa después de ajustar `UserRoleMigrationTest` para revertir
  explícitamente la migración de roles; la prueba usaba `--step=1`, que dejó de
  apuntar a ella al agregar la migración de solicitudes.

## Próximo paso

Continuar con GT-HU-002 y definir la transición administrativa que devuelve una
solicitud para correcciones.
