# Handoff: GT-HU-042 — Destino de Seguimiento para Supervisor

## Estado

La tarea está activa y aún no tiene cambios de aplicación. Se inspeccionaron
los destinos local y SAML y las protecciones actuales.

## Hallazgo que requiere decisión

El login local rechaza a Supervisores no verificados o sin empresa. En cambio,
`EnsureSupervisor` solo valida rol y correo verificado; un Supervisor
verificado sin empresa puede abrir Seguimiento, aunque su consulta quede vacía.
El flujo SAML reutiliza cuentas existentes y no comprueba empresa antes de
autenticarlas. Cambiar el destino SAML hará visible esta diferencia.

La persona solicitante confirmó añadir el requisito de empresa al middleware
compartido de Supervisor, para que la denegación aplique a todas sus rutas y
también a las sesiones SAML.

## Resultado

El login local y SAML de un Supervisor válido dirige a Seguimiento. El guard
compartido ahora requiere rol Supervisor, correo verificado y empresa asignada;
el filtro existente por empresa/prácticas se conserva. Las pruebas dirigidas
pasaron: 38 tests y 223 assertions. Pint pasó en los seis archivos PHP
modificados.

La persona solicitante aprobó el cierre y la tarea quedó archivada. El cambio
se compromete en `sprint/v1.2-1-acceso-navegacion-rol`; no se integrará el
sprint a `develop` hasta completar y validar sus cuatro historias.
