# Handoff: gt-hu-019-acceso-alumnos-saml2

## Estado

Tarea cerrada y archivada en `sprint/1-catalogo-solicitud`, asignada a `backend`.
Implementación del prototipo simulado completa y validada en Docker. La
configuración real del IDP se aplaza hasta que se proporcionen sus datos.

## Alcance confirmado

- Una aserción SAML válida con correo existente inicia sesión con ese perfil;
  si no existe, crea una cuenta local con rol único de alumno.
- La persona solicitante eligió `uCorreo` (email institucional) como
  identificador único, en vez de `student_number`/`worker_number`, cuya
  disponibilidad no está confirmada.
- Con accesos posteriores preservar el rol local asignado por Coordinación,
  incluso si se cambió de alumno a tutor.
- Reemplazar el booleano actual `is_coordinator` por `role` con valores únicos
  `coordinator`, `student`, `tutor`, `supervisor`, y migrar la protección
  actual de Coordinación.
- Simulación de login solo para desarrollo/pruebas; producción debe requerir
  aserción SAML válida.
- HU-019 precede HU-014. Supervisores locales son HU-020; gestión de perfiles y
  roles es HU-021, ambas fuera de esta tarea y sin sprint asignado.
- Para el prototipo, `.env.example` habilita el simulador con valores
  sintéticos `example.test`. Replica los claims `uCorreo`, `uNombre`, `sn`,
  `givenName` y `displayName` con las variables `SAML_SIMULATOR_*`; no se
  guardaron los datos personales facilitados en la conversación.

## Ejemplo técnico

`/home/fabian/code/redi` usa `aacotroneo/laravel-saml2 ^2.1` en Laravel 8 con
PHP `^7.3|^8.0`; nanis usa Laravel 13/PHP 8.4. La compatibilidad del paquete no
es apropiada para esta aplicación. Se eligió `onelogin/php-saml` 4.3.2, probado
con login iniciado por SP, metadata y ACS de respuesta inválida dentro de
Laravel 13/PHP 8.4. No se ha probado todavía una respuesta firmada válida de
un IDP real. No copiar del ejemplo IDs numéricos, datos de simulación ni
credenciales.

## Pendientes posteriores (integración con IDP real)

- La configuración real (metadata/certificado, claim efectivo y cuenta de
  pruebas) queda diferida por decisión de la persona solicitante.
- Logout invalida la sesión local; SLO de IDP queda fuera del alcance.

## Próximos pasos

1. Dejar HU-014 activa para `frontend` en Sprint 1.
2. Cuando llegue la integración IDP, configurar valores solo en `.env` y validar respuesta firmada, claim y SLO.
