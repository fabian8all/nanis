# Notas de trabajo: gt-hu-001-solicitud-practica

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- La devolución para correcciones depende de GT-HU-002; esta historia conserva el estado y prepara el bloqueo de edición sin implementar la interfaz administrativa.

## Evidencia recopilada
- Confirmaciones del usuario: campos acordados obligatorios; término posterior al inicio; empresa activa al enviar; un trámite simultáneo por alumno; edición solo tras devolución de Coordinación; confirmación sencilla sin folio.
- Aclaración del usuario: la carta tiene espacios para llenar a mano; la fecha debe ser la del día en que se imprime; se firma físicamente y no se conserva copia firmada.
- Se leyó la carta fuente sin modificarla. Se conservó su texto y sus espacios manuscritos en la vista de impresión; solo se sustituye la fecha fija por la fecha de impresión.
- Se leyó la carta fuente sin modificarla. Se conservó su texto y sus espacios manuscritos en la vista de impresión; solo se sustituye la fecha fija por la fecha de impresión.
- GT-HU-014 guarda la empresa seleccionada en sesión bajo `practice.selected_company_id`.

## Callejones sin salida (para no repetirlos)
- No inferir que los documentos fuente tienen ejemplos rellenados; implementar la impresión con una vista propia y no modificar esos archivos.
