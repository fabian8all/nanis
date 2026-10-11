# Handoff: gt-hu-005-plan-trabajo

## Estado

GT-HU-005 implementada, validada y comprometida como `23bf1bb` en
`sprint/3-aceptacion-empresa`. HU-004 se comprometió como `00db4dc`; Sprint 3
no debe integrarse en `develop` hasta revisar y validar la rama completa.

## Criterios confirmados

- Tras aprobarse la carta de aceptación, el alumno registra una o más
  actividades con descripción, fechas de inicio/término, responsable de la
  empresa en texto libre y resultado esperado.
- No hay fecha límite. El término no puede anteceder al inicio, pero las
  actividades no se limitan a las fechas de la solicitud.
- La revisión es secuencial: supervisor que cargó la aceptación aprobada y
  luego Coordinación. Ambos pueden aprobar o devolver con comentario
  obligatorio.
- El alumno solo puede editar tras devolución. Cada reenvío crea una versión
  completa nueva y vuelve primero al supervisor.
- El alumno propietario, el supervisor asignado y Coordinación consultan el
  historial; solo el revisor pendiente puede actuar.

## Implementación

- Se añadieron tablas para planes, versiones y actividades.
- La captura y corrección del alumno se vincula a su solicitud aprobada y se
  habilita cuando existe aceptación empresarial aprobada.
- El supervisor asignado puede revisar y devolver/aprobar; Coordinación actúa
  después de la aprobación del supervisor. Los estados y comentarios quedan
  asociados a cada versión.
- Se añadieron bandejas de supervisor/Coordinación, vistas históricas y enlaces
  desde la solicitud del alumno.
- Migración `2026_10_11_000010_create_practice_plans_tables` aplicada.

## Validación

- `docker exec project-app-app-1 php artisan test --no-coverage`: 62 pruebas y
  422 aserciones aprobadas.
- Pint `--test` pasó para los 14 archivos PHP de HU-005.
- `http://nanis.net` responde HTTP 200.
- Las pruebas de flujo verifican habilitación tras aceptación, fechas,
  asignación del supervisor, comentarios obligatorios, devoluciones,
  versionado, aprobación final e historial de solo lectura.

## Siguiente paso

Revisar el diff completo del Sprint 3 y validar HU-004/HU-005 juntas antes de
integrarlo a `develop`. Las modificaciones ajenas preexistentes continúan fuera
de ambos commits.
