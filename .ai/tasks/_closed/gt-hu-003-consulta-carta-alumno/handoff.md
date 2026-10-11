# Handoff: gt-hu-003-consulta-carta-alumno

## Estado

GT-HU-003 implementada y validada en `sprint/2-revision-cartas`; no integrar el
sprint a `develop` hasta que también se cierre HU-004 y se valide el sprint.

## Implementación

- Desde su solicitud aprobada, el alumno ve un enlace a la carta de presentación.
- Una vista imprimible compartida con Coordinación permite consultar e imprimir
  la carta o guardarla como PDF mediante el diálogo del navegador.
- El componente estudiantil exige rol alumno, filtra por propietario y estado
  aprobado, y responde 404 para cartas ajenas o no aprobadas.
- No se guardan archivos PDF ni se registran consultas, descargas, ni fechas o
  resultados de entrega.

## Validación

- `docker exec project-app-app-1 php artisan test --no-coverage`: 45 pruebas y
  246 aserciones aprobadas.
- Pint `--test` pasó para 6 archivos PHP modificados.
- Sin sesión, `/alumno/solicitud` y `/alumno/cartas/1` respondieron HTTP 302.
- Se reconstruyó el entorno con `docker compose up --build -d`.
- Se probaron acceso autenticado, rol, propiedad, aprobación, enlace desde la
  solicitud y conservación de la impresión de Coordinación.
- La salida impresa comparte la plantilla HTML/CSS existente; no se hizo una
  revisión visual manual del PDF desde navegador.

## Decisión confirmada

El alumno consulta y descarga/imprime solo su propia carta aprobada. No registrar
descargas ni entrega a la empresa.

## Siguiente paso

Continuar con GT-HU-004, registro de aceptación de la empresa.
