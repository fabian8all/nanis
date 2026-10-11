# Handoff: gt-hu-002-revision-solicitud

## Estado

GT-HU-002 implementada y validada en `sprint/2-revision-cartas`. Integrar el
sprint a `develop` solo después de cerrar y validar también GT-HU-003.

## Implementación

- Coordinación autenticada puede revisar solicitudes pendientes y aprobarlas o
  devolverlas con comentario obligatorio; no existe rechazo definitivo.
- Cada decisión y comentario queda en historial, incluso tras una corrección y
  reenvío del alumno.
- La aprobación genera una copia independiente de la carta con folio anual
  `CP-AAAA-0001`. Los cambios permitidos se limitan a destinatario, puesto,
  lugar y fechas; la solicitud original y la identidad de alumno/empresa no se
  alteran.
- La carta se presenta en una vista imprimible protegida para firma física de
  Coordinación. El NSS y número de cuenta no aparecen en bandeja, detalle de
  revisión ni historial; el número de cuenta se imprime como `0000-0000`.
- El formulario del alumno requiere NSS de 11 dígitos y cuenta de 8 dígitos.
  Ambos valores usan cast cifrado de Laravel y se conservan en el expediente.

## Decisiones confirmadas

- Solo Coordinación puede consultar el expediente protegido y emitir/imprimir la
  carta; HU-003 queda a cargo de consulta/descarga por el alumno.
- No se incorporaron valores de ejemplo ni el archivo fuente de la plantilla
  oficial.
- La firma es física; no se almacena una copia firmada.

## Validación

- `docker exec project-app-app-1 php artisan test --no-coverage`: 42 pruebas y
  228 aserciones aprobadas.
- Pint `--test` para los PHP modificados: 12 archivos aprobados.
- `git diff --check` requiere considerar CRLF como fin de línea preexistente en
  los documentos tocados; revisar con `core.whitespace=cr-at-eol`.
- Las migraciones HU-002 se aplicaron al MySQL local; la ruta administrativa
  protegida redirige sin sesión.
- Se actualizaron pruebas para verificar el formato de NSS/cuenta con datos
  ficticios. Las fuentes con información personal de `docs/formatos/` siguen
  fuera del commit.

## Siguiente paso

Crear el commit de esta historia en `sprint/2-revision-cartas` y continuar con
GT-HU-003 (consulta/descarga de carta por el alumno).
