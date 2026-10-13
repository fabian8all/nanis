# Aplicación

Base de desarrollo con Laravel 13, Livewire 4, Alpine.js (incluido por Livewire), Bootstrap 5 y MySQL 8.4. No utiliza Vite ni Tailwind. Bootstrap se carga desde CDN; Livewire distribuye su runtime y Alpine.js.

## Requisitos

- Docker Desktop con Docker Compose.
- En este checkout, Ubuntu sobre WSL no tiene habilitada la integración de Docker Desktop. Ejecuta Compose desde Windows PowerShell en la ruta UNC del proyecto o habilita la integración WSL para usar el comando desde Ubuntu.

## Inicio

1. Copia `.env.example` a `.env`.
2. En `.env`, define `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` y `DB_ROOT_PASSWORD`. Usa valores locales propios y contraseñas seguras; el archivo `.env` está excluido de Git.
3. Desde Windows PowerShell, para este checkout:

```powershell
Set-Location '\\wsl.localhost\Ubuntu\home\fabian\code\nanis\project-app'
docker compose up --build
```

Abre <http://nanis.net>. Nginx escucha en el puerto 80 local y enruta el hostname `nanis.net` a Laravel. También queda disponible <http://localhost:8000> para diagnóstico directo. La primera ejecución instala las dependencias PHP en el volumen `vendor` y genera `APP_KEY` si hace falta. Las migraciones no se ejecutan automáticamente; MySQL inicia con la base vacía indicada por `DB_DATABASE`.

El código de la aplicación se incluye en la imagen para permitir ejecutar Compose cuando el repositorio está en WSL sin integración de Docker Desktop. Después de cambios en archivos de la aplicación, ejecuta `docker compose up --build -d` para actualizar la imagen.

Comandos habituales:

```sh
docker compose exec app php artisan migrate
docker compose exec app php artisan app:create-coordinator
docker compose exec app php artisan test
docker compose down
```

Después de ejecutar las migraciones, crea la primera cuenta de Coordinación con
`app:create-coordinator`. El comando solicita nombre, email y contraseña en la
terminal; no hay cuentas predeterminadas ni credenciales guardadas en el
repositorio. El acceso administrativo se realiza por `/login`.

Para poblar una instalación local con datos completamente ficticios, define
`DEMO_PASSWORD` en `.env` y ejecuta:

```sh
docker compose exec app php artisan db:seed --class=DevelopmentDatasetSeeder
```

El seeder crea 20 empresas, 1 Coordinación, 5 asesores, 1 supervisor y 50
alumnos, con solicitudes desde revisión inicial hasta reporte final aprobado.
Los usuarios usan el valor local de `DEMO_PASSWORD`; las cuentas de alumno
pueden iniciar sesión por contraseña solo en `local`/`testing` y siguen usando
SAML fuera de esos entornos. El seeder rechaza cualquier entorno distinto de
`local`/`testing`; no uses contraseñas demo en producción. Los correos de
Coordinación y Supervisor se ajustan con `DEMO_COORDINATOR_EMAIL` y
`DEMO_SUPERVISOR_EMAIL`; `DEMO_USER_EMAIL_DOMAIN` configura el dominio de las
cuentas `asesorN` y `alumnoN`. Los valores de ejemplo usan `example.test`;
mantén cualquier dirección institucional o contraseña real solo en el `.env`
local ignorado por Git.

Coordinación puede invitar supervisores desde el catálogo de empresas. La
invitación verifica el correo mediante un enlace de un solo uso (vence en siete
días) y permite establecer una contraseña. En el entorno local, el mailer
predeterminado `log` escribe la invitación en `storage/logs/laravel.log`; para
enviar correo real, configura `MAIL_MAILER` y sus variables SMTP en `.env`.
Las cartas de aceptación aceptan PDF, JPG o PNG hasta 20 MB y se guardan en
almacenamiento privado, accesible solo a Coordinación y al supervisor de la
empresa correspondiente.

Después de aprobarse la aceptación, el alumno registra el plan de trabajo por
actividades (descripción, fechas, responsable y resultado esperado). El
supervisor que aceptó la práctica revisa primero; Coordinación realiza la
revisión final. Si cualquiera devuelve el plan debe escribir observaciones; el
alumno puede corregirlo y reenviarlo al supervisor. Se conserva el historial
de versiones.

### Acceso institucional SAML2 (alumnos)

El inicio institucional solo se habilita al configurar `SAML2_ENABLED=true`,
`SAML2_IDP_ENTITY_ID`, `SAML2_IDP_SSO_URL` y `SAML2_IDP_X509_CERT` en el `.env`.
El certificado debe ser el certificado público de firma del IDP. `uCorreo` es
el atributo predeterminado que identifica la cuenta; `SAML2_EMAIL_ATTRIBUTE` y
`SAML2_NAME_ATTRIBUTE` permiten ajustarlo al contrato real del IDP. El proveedor
de identidad debe registrar la URL de ACS `/auth/saml/acs` y el entity ID
publicado por `/auth/saml/metadata` (o el valor explícito de
`SAML2_SP_ENTITY_ID`). No habilites la simulación fuera de `local` o `testing`.

Para desarrollo, `.env.example` habilita `SAML_SIMULATOR=true` con valores
sintéticos `example.test` en `SAML_SIMULATOR_EMAIL`, `SAML_SIMULATOR_NAME`,
`SAML_SIMULATOR_FIRSTNAME` y `SAML_SIMULATOR_LASTNAME`. El acceso de prueba aparece en `/login`; el
controlador también limita la ruta a `local` y `testing`. No versionar datos
personales en esos valores. Un primer acceso crea una cuenta `student`; correos
ya registrados usan su cuenta y rol existentes sin sobrescribirlos.

La correlación usa un `RelayState` aleatorio, de un solo uso y con vigencia de
cinco minutos, guardado en el cache configurado; así el callback HTTP-POST no
depende de que el navegador envíe la cookie de sesión en una solicitud
cross-site. En despliegues con varias réplicas, configura `CACHE_STORE` con un
almacenamiento compartido.

Los datos de MySQL persisten en el volumen `mysql-data`. `docker compose down -v` elimina esos datos y el volumen de dependencias.

Compose exige esas variables y falla si no están definidas. No compartas ni versionas `.env` y no reutilices credenciales locales en producción. Nginx no configura TLS; el entorno es para desarrollo local.
