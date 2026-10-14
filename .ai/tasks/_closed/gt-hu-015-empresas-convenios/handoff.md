# Handoff: gt-hu-015-empresas-convenios

## Estado de la tarea

Implementación de GT-HU-015 completada y validada en la rama
`sprint/1-catalogo-solicitud`. La tarea está archivada y los cambios quedaron
en el commit `54c0edf`. No cambiar de rama antes de continuar el Sprint 1.

## Decisiones confirmadas

- Datos de empresa para mostrar: nombre, dirección, teléfono, email y persona
  de contacto.
- Coordinación administra altas y bajas manualmente. Dar de baja oculta la
  empresa de nuevas solicitudes, pero conserva referencias existentes y no
  afecta prácticas iniciadas.
- Login local de Laravel, acceso de administración solo para Coordinación.
  Mantener Bootstrap/Livewire y no añadir Vite ni Tailwind.
- Crear la cuenta inicial con `php artisan app:create-coordinator`, solicitando
  nombre, email y contraseña interactivamente; no versionar credenciales.

## Implementación completada

- Migraciones aplicadas para `users.is_coordinator` y `companies`; no se
  sembraron datos de usuarios.
- Modelo `Company`, permiso de Coordinación en `User` y middleware
  `EnsureCoordinator`.
- Login local en `/login`, limitador de intentos, logout protegido por
  autenticación y rutas de catálogo protegidas.
- Comando `php artisan app:create-coordinator`, solicita nombre, email y
  contraseña interactiva (12 caracteres mínimo y confirmación); no creó una
  cuenta real y no incluye credenciales en el repositorio.
- CRUD Livewire del catálogo con los campos confirmados y baja reversible que
  conserva el registro para referencias futuras.
- Seeder de ejemplo eliminado; README actualizado con los comandos de
  migración, aprovisionamiento y pruebas.
- Pruebas Feature cubren acceso, login coordinador/no-coordinador,
  administración, validación, baja/reactivación y comando de creación.

Validación ejecutada: Docker reconstruido; migraciones MySQL aplicadas;
`php artisan test --no-coverage` pasa 11 pruebas (41 aserciones); Pint PASS
en 13 archivos PHP; `GET /login` responde HTTP 200. La advertencia de lectura
de `.env` reportada en una ejecución anterior del runner no se reprodujo en la
última corrida.

## Siguiente paso

1. Abrir la siguiente historia de Sprint 1, asignada por el orquestador.

Las fuentes de `../../../../docs/v1.0/formatos/` siguen sin agregarse: algunas imágenes
contienen ejemplos diligenciados con datos personales; no versionarlas sin
saneamiento. HU-014, HU-001 y HU-018 siguen pendientes para tareas posteriores
del Sprint 1, asignadas por el orquestador según sus necesidades.
