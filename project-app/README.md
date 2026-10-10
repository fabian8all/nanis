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
docker compose exec app php artisan test
docker compose down
```

Los datos de MySQL persisten en el volumen `mysql-data`. `docker compose down -v` elimina esos datos y el volumen de dependencias.

Compose exige esas variables y falla si no están definidas. No compartas ni versionas `.env` y no reutilices credenciales locales en producción. Nginx no configura TLS; el entorno es para desarrollo local.
