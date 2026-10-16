# Plan de ejecución: gt-hu-053-verificar-simulador-saml

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Verificar el montaje del `.env` y localizar el uso de la variable
  sin consultar valores locales.
- [x] Paso 2 — Documentar el contrato seguro de configuración, sin secretos ni
  cambios de código.
- [x] Paso 3 — Completar handoff y obtener aprobación de cierre antes del
  commit de la tarea de verificación.

## Estado actual
La persona solicitante confirmó que esta HU es solo verificación/documentación,
sin desarrollo nuevo. Compose monta `project-app/.env` como
`/var/www/html/.env:ro`; la clave `SAML_SIMULATOR_EMAIL` está presente sin
exponer su valor. `config/services.php` la lee, el simulador usa su clave
configurada y no existe caché de configuración. Compose es válido y Git ignora
el `.env` local. No se modificaron código ni configuración local. La persona
solicitante aprobó el cierre y la tarea quedó archivada; crear el commit
documental.
