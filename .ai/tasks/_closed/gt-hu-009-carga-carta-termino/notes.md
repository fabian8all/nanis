# Notas de trabajo: gt-hu-009-carga-carta-termino

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- Un registro por práctica que contiene el archivo vigente y estado/comentario; no se crea historial de versiones.
- Coordinación valida manualmente el contenido de la carta definido en HU-008; no se pretende extraer/verificarlo automáticamente.
- El acceso se limita al alumno propietario y a Coordinación; no exponemos la carta a Tutor/Supervisor.
- Una fila por práctica en `practice_termination_letters`: ruta privada, nombre original, MIME, tamaño, estado, revisor/comentario y fechas de envío/revisión.
- Estados: `pending_review`, `returned`, `accepted`; Coordinación confirma explícitamente que revisó los campos obligatorios antes de aceptar.
- Las cargas solo se ofrecen para prácticas aprobadas; no se valida por código el hito de 240 horas porque el sistema no tiene contador.
- Reemplazo bajo bloqueo de la solicitud: crear el nuevo archivo, actualizar la fila única y retirar el anterior; la UI nunca presenta versiones históricas.

## Evidencia recopilada
- La carga de cartas de aceptación demuestra almacenamiento privado, locks transaccionales y validación MIME en `app/Livewire/Supervisor/PracticeAcceptances/Upload.php`.
- La revisión con comentario obligatorio y transiciones está en `app/Livewire/Coordinator/PracticeAcceptances/Review.php`.
- `app/Http/Controllers/Coordinator/PracticeAcceptanceFileController.php` muestra el patrón de descarga autenticada con `nosniff`.

## Callejones sin salida (para no repetirlos)
- No basar permisos solo en esconder enlaces; verificar rol y propietario en cada acción/ruta.
- No conservar el documento anterior tras reemplazar la carta.
- No usar enlaces públicos ni confiar únicamente en rutas ocultas; cada acceso al archivo valida rol y propiedad de la práctica.
