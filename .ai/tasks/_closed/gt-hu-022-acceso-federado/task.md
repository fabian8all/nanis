# Tarea: gt-hu-022-acceso-federado

**Creada:** 2026-10-14 · **Tamaño:** small · **Owner:** frontend · **Rol:** Frontend (`software/frontend`)

## Objetivo
Hacer reconocible el acceso federado para el alumnado y ubicarlo después del
inicio de sesión local sin alterar el destino SAML existente.

## Incluido en el alcance
- Cambiar el rótulo del botón de acceso del alumno a **Acceso federado UCOL**.
- Mostrar el botón debajo de **Iniciar sesión**.
- Conservar su enlace al flujo SAML institucional o al simulador local según la
  configuración.
- Añadir o ajustar una prueba de la etiqueta, ubicación y destino del botón.

## Dependencias
- Ninguna.

## Explícitamente fuera de alcance
- Cambiar el protocolo, configuración o aprovisionamiento SAML.
- Modificar el formulario o el comportamiento de autenticación local.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `project-app/resources/views/auth/login.blade.php`
- `project-app/tests/Feature/SamlStudentAuthenticationTest.php`
- `.ai/tasks/gt-hu-022-acceso-federado/**`
- `.ai/HANDOFF.md`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/v1.1/backlog/backlog-candidato.md` (GT-HU-022)
- `project-app/resources/views/auth/login.blade.php`
- `project-app/tests/Feature/SamlStudentAuthenticationTest.php`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
