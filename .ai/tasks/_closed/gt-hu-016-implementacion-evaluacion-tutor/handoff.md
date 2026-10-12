# Handoff: gt-hu-016-implementacion-evaluacion-tutor

## Objetivo
Implementar captura, envío inmutable, cálculo ponderado y consultas autorizadas de la evaluación final del tutor (GT-HU-016).

## Decisiones confirmadas
- La evaluación la realiza el tutor asignado que aprobó el reporte final.
- Se habilita tras aprobar HU-007; no depende de completar las 240 horas ni de HU-006.
- Al crearla fija la versión aprobada del reporte y la versión publicada vigente de la rúbrica Tutor.
- Una evaluación por práctica, con borrador editable hasta un único envío inmutable.
- Todos los criterios son obligatorios; no se permite omitir ni marcar “No aplica”.
- Resultado ponderado normalizado de 0 a 100 con detalle por criterio.
- El alumno propietario, tutor evaluador y Coordinación consultan el resultado; retención indefinida.

## Hallazgo operativo
La rúbrica Tutor está actualmente en borrador vacío y sin escala institucional recuperada. La historia debe bloquear el inicio con un mensaje claro hasta que Coordinación la complete y publique mediante HU-017; no inventar, precargar ni publicar criterios.

## Estado
Implementación terminada y desplegada en el entorno local. La migración `2026_10_11_000013_create_practice_evaluations_tables` está aplicada. El flujo fija la versión aprobada del reporte y la rúbrica Tutor publicada, conserva una copia de criterios/escalas, exige todos los niveles válidos, calcula 0–100 con el servicio existente y bloquea edición al enviar. Tutor, alumno propietario y Coordinación consultan el resultado; las demás identidades reciben 403/404.

## Operación
`nanis.net/login` responde HTTP 200 y `/tutor/evaluaciones` redirige a login sin autenticación. Se cargaron datos ficticios mediante `DemoDataSeeder`: Coordinación `coordinacion@nanis.test`, asesor/tutor `asesor@nanis.test`, Supervisor `supervisor@nanis.test` y alumno `alumno@nanis.test`. La contraseña temporal se generó para las cuentas con acceso local y no se registra en este handoff ni en Git. El alumno entra mediante el simulador SAML local, configurado en el `.env` runtime para reutilizar la cuenta demo; se verificaron las cuatro pantallas.

La práctica demo tiene aceptación aprobada, plan aprobado y reporte aprobado por el tutor asignado. No se creó evaluación porque la rúbrica Tutor institucional permanece vacía/no publicada; Coordinación debe completarla y publicarla en HU-017 para continuar sin criterios inventados.

Validación: 4 pruebas/38 aserciones dirigidas aprobadas; Pint PASS en 14 PHP. La suite completa ejecutó 75 pruebas/513 aserciones y mostró un fallo no relacionado en `CoordinatorPracticeRequestReviewTest::test_approval_creates_yearly_letter_folio_without_changing_original_request`, debido a representación de fechas SQLite con hora en la aserción. El contenedor se actualizó sin recrearlo, preservando su `.env` runtime y APP_KEY; las copias temporales aisladas se retiraron al terminar. No versionar fuentes en `docs/formatos/`.
