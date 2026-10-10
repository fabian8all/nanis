# Resumen del proyecto

- **Producto:** prototipo web para prácticas operativas y de mandos medios de Gestión Turística, no estancias profesionales. Conclusión a las 240 horas, con una evaluación final de empresa/supervisor y un reporte final, sin evaluaciones ni reportes intermedios. Incluye además una encuesta de satisfacción a empresa, respondida por el supervisor directo cuando coordinación la solicite; solo coordinación verá las respuestas. Se distingue de la evaluación terminal y el cuestionario está por definir. Roles candidatos: alumno, asesor académico, coordinación y empresa; faltan tareas/permisos.
- **Fuentes iniciales:** `docs/planeacion/Ochoa_Ileana_R4_U2.pdf` describe la visión/prototipo; `Ochoa_Ileana_R5_U2- observaciones.pdf` aporta criterios éticos y de participación. Reto 4 es la base candidata acordada para el backlog, no una especificación de requisitos aprobada.
- **Flujo candidato:** `docs/casos-de-uso/flujo-candidato-practica.md` registra solicitud → presentación → aceptación → plan → 240 horas → evaluación/reporte final → carta de término; sin actividades intermedias de evaluación o reporte.
- **Backlog candidato:** `docs/backlog/candidatos-iniciales.md`; historias por etapa, todavía faltan validación de criterios, prioridades, estimaciones y sprints.
- **Aplicación:** `project-app/`, base Laravel 13 + Livewire 4, PHP 8.4, MySQL 8.4, Bootstrap 5 y Alpine.js incluido por Livewire. Sin Vite ni Tailwind.
- **Desarrollo local:** Docker Compose; instrucciones en `project-app/README.md`.
- **Acceso local:** `http://nanis.net` a través de `nginx-proxy`; base MySQL `project`, usuario `nanis` (credenciales solo en `.env`, no en memoria versionada).
- **Estado del entorno:** servicios iniciados; reconstruir la imagen de la aplicación al cambiar código porque Docker Desktop no monta directamente esta distribución WSL.
- **Documentación del producto:** `docs/` (requisitos, casos de uso, backlog, sprints y manuales).
- **Continuidad y memoria IA:** `.ai/`; no mover aquí artefactos funcionales del producto.
- **Tarea activa:** `.ai/tasks/planificacion-sprints-agentes/`, rol `gestion-proyecto`; hay borrador de cinco sprints por resultado, con una persona desarrolladora a tiempo completo apoyada por agentes IA; sin duración de sprint ni horas semanales fijadas.
