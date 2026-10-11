# Notas de trabajo: gt-hu-004-aceptacion-empresa

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Decisiones confirmadas

- El supervisor de empresa carga la carta de aceptación en la plataforma; Coordinación la revisa y puede aprobarla o devolverla.
- Coordinación invita al supervisor; el enlace verifica posesión del correo y el supervisor define contraseña.
- La cuenta de supervisor queda vinculada a una empresa. Solo consulta cartas emitidas de esa empresa pendientes de aceptación; no ve la solicitud original.
- El supervisor sí ve la carta oficial completa, incluidos NSS y cuenta, porque es el documento que recibirá su empresa.
- Se aceptan PDF, JPG/JPEG y PNG hasta 20 MB.
- Cada carga y sus decisiones/comentarios se conserva como historial restringido; una carta aprobada cierra nuevas cargas.

## Restricciones encontradas

- `UserRole::Supervisor` existe, pero el login local solo acepta Coordinación.
- No hay vínculo entre `User` supervisor y `Company`; no hay flujos de invitación ni carga de archivos implementados.
- El disco local predeterminado de Laravel guarda en `storage/app/private`, adecuado para los documentos si no se crea una ruta pública.
- El procedimiento institucional 2025 describe entrega física de la aceptación; la persona solicitante confirmó usar en el prototipo la carga digital directa de la empresa.

## Implementación y cierre

- El enlace de invitación se almacena como hash, vence a los siete días y solo se puede consumir una vez; la cuenta queda verificada y asociada a una empresa.
- Cada carga se almacena en el disco privado `local`, se valida contra PDF/JPG/JPEG/PNG y 20 MB, y crea una versión sin sobrescribir las anteriores.
- Coordinación puede consultar y descargar el historial completo, incluidos comentarios y decisiones después de aprobar. Las acciones de revisión solo aparecen para una versión pendiente.
- El mailer de desarrollo sigue siendo `log`; configurar SMTP para entregar invitaciones por correo real.
- Validación final: `php artisan test --no-coverage` con 58 pruebas/365 aserciones aprobadas; Pint `--test` para PHP de HU-004; migraciones aplicadas; `http://nanis.net` respondió HTTP 200.
- Decisiones de implementación por confirmar si se amplía el alcance: invitación de siete días y una empresa por cuenta de supervisor.
