# Plan de ejecución: gt-hu-015-empresas-convenios

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Acordar datos mínimos de empresa/convenio y reglas de vigencia/historial; no implementar antes de resolver criterios.
- [x] Paso 2 — Diseñar e implementar el modelo y la administración según los criterios confirmados.
- [x] Paso 3 — Agregar pruebas, validar el flujo, actualizar handoff, hacer commit y cerrar la tarea.

## Estado actual
Paso 1 completo. Se confirmaron los campos de visualización: nombre de empresa, dirección, teléfono, email y persona de contacto; Coordinación hará altas y bajas manuales. La baja oculta para futuras solicitudes y conserva referencias existentes. La persona solicitante confirmó login local de Laravel con permisos de Coordinación, sin Vite/Tailwind, y la creación inicial mediante un comando Artisan interactivo que no persiste credenciales.

Implementación y pruebas completadas en `project-app`: login HTTP protegido por limitador, middleware de Coordinación, comando interactivo `app:create-coordinator`, administración Livewire y migraciones de usuarios/empresas. PHPUnit pasa 11 pruebas (41 aserciones); Pint pasó para 13 archivos PHP. Las migraciones corrieron en MySQL; no se aprovisionó una cuenta real porque requiere que la persona usuaria ingrese su contraseña en la terminal. Solo falta incorporar el worktree validado en el commit de la historia.
