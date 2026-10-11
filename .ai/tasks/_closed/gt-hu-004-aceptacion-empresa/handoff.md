# Handoff: gt-hu-004-aceptacion-empresa

## Estado

GT-HU-004 implementada y validada en `sprint/3-aceptacion-empresa`. Sprint 2
está integrado en `develop` hasta `6f2bbbc`; no integrar Sprint 3 hasta cerrar
GT-HU-005.

## Implementación

- Coordinación puede invitar supervisores; el token de un solo uso se conserva
  como hash, vence a los siete días y activa una cuenta verificada asociada a
  una empresa.
- La cuenta supervisora accede solo a las cartas emitidas de su empresa; no
  tiene acceso al detalle de solicitudes.
- El supervisor carga PDF/JPG/JPEG/PNG de hasta 20 MB. Los archivos se guardan
  en el disco privado de Laravel y cada reenvío crea una versión independiente.
- Coordinación aprueba o devuelve cada envío (comentario obligatorio al
  devolver). El historial de versiones, estados y comentarios queda disponible
  para Coordinación incluso después de aprobar; una aprobación cierra nuevas
  cargas.
- Los archivos solo se sirven a Coordinación y al supervisor de la empresa
  correspondiente.

## Validación

- `docker exec project-app-app-1 php artisan test --no-coverage`: 58 pruebas y
  365 aserciones aprobadas.
- Pint `--test` pasa en los archivos PHP de HU-004.
- Migraciones 000007–000009 aplicadas a MySQL.
- `docker compose up --build -d app` reconstruyó el servicio; PHP informa
  `upload_max_filesize=20M` y `post_max_size=25M`.
- `http://nanis.net` responde HTTP 200.

## Decisiones pendientes de confirmar

- Se implementó una vigencia de siete días para la invitación.
- Cada cuenta de supervisor pertenece a una sola empresa; la empresa puede
  tener varias cuentas supervisoras.
- El mailer local es `log`; SMTP se configura antes de enviar invitaciones
  reales.

## Siguiente paso

Iniciar GT-HU-005, plan de trabajo de la práctica, en la rama del Sprint 3.
Mantener fuera del commit `docs/formatos/`, los archivos `Zone.Identifier` y
las modificaciones preexistentes a hooks y herramientas de Continuum.
