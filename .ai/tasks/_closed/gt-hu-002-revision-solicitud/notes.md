# Notas de trabajo: gt-hu-002-revision-solicitud

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- El comentario de devolución se conserva como parte del historial y se muestra al alumno más reciente.

## Evidencia recopilada
- Coordinación solo aprueba o devuelve; no se permite rechazo definitivo.
- Devolver exige comentario y cada revisión debe conservar decisión/comentario incluso tras el reenvío.
- La copia de carta guarda ajustes sin modificar la solicitud original.
- Solo se ajustan destinatario, puesto, lugar y fechas; identidad del alumno y empresa permanecen fijas.
- El folio es anual con formato `CP-AAAA-0001`.
- La carta se basa en el formato oficial y se imprime para firma física de Coordinación; HU-003 permitirá consulta/descarga por el alumno.
- El formato oficial contiene No. de IMSS. El usuario confirmó incorporarlo obligatorio en la solicitud y conservarlo cifrado en el expediente; solo lo consultan alumno propietario y Coordinación.
- La plantilla también incluye el número de cuenta, que el usuario confirmó como obligatorio; capturar 8 dígitos y presentarlos en la carta como `0000-0000`. Se cifra y mantiene el mismo acceso restringido.
- HU-001 mantiene el estado `returned` editable y `pending` de solo lectura.

## Callejones sin salida (para no repetirlos)
- No incluir en el repositorio valores de ejemplo de documentos fuente.
- No mostrar ni serializar NSS ni cuenta en la bandeja, detalle de revisión ni bitácora; solo aparecen en el expediente del alumno y en la vista imprimible protegida de Coordinación.
