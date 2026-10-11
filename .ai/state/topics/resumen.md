# Resumen del proyecto

- **Producto:** prototipo web para prácticas operativas y de mandos medios de Gestión Turística, no estancias profesionales. Conclusión a las 240 horas, con evaluaciones finales separadas de supervisor y tutor, y un reporte final; sin evaluaciones ni reportes intermedios. Coordinación podrá modificar ambas rúbricas y cada evaluación conserva su versión. En la solicitud el alumno acepta e imprime la carta de no responsiva para firma física; el sistema no guarda copia firmada.
- **Fuentes iniciales:** `docs/planeacion/Ochoa_Ileana_R4_U2.pdf` describe la visión/prototipo; `Ochoa_Ileana_R5_U2- observaciones.pdf` aporta criterios éticos y de participación. Reto 4 es la base candidata acordada para el backlog, no una especificación de requisitos aprobada.
- **Flujo candidato:** `docs/casos-de-uso/flujo-candidato-practica.md` registra solicitud → presentación → aceptación → plan → 240 horas → evaluación/reporte final → carta de término; sin actividades intermedias de evaluación o reporte.
- **Backlog candidato:** `docs/backlog/candidatos-iniciales.md`; historias por etapa, todavía faltan validación de criterios, prioridades, estimaciones y sprints.
- **Aplicación:** `project-app/`, base Laravel 13 + Livewire 4, PHP 8.4, MySQL 8.4, Bootstrap 5 y Alpine.js incluido por Livewire. Sin Vite ni Tailwind.
- **Desarrollo local:** Docker Compose; instrucciones en `project-app/README.md`.
- **Acceso local:** `http://nanis.net` a través de `nginx-proxy`; base MySQL `project`, usuario `nanis` (credenciales solo en `.env`, no en memoria versionada).
- **Estado del entorno:** servicios iniciados; reconstruir la imagen de la aplicación al cambiar código porque Docker Desktop no monta directamente esta distribución WSL.
- **Documentación del producto:** `docs/` (requisitos, casos de uso, backlog, sprints y manuales).
- **Continuidad y memoria IA:** `.ai/`; no mover aquí artefactos funcionales del producto.
- **Última historia cerrada:** `.ai/tasks/_closed/gt-hu-015-empresas-convenios/` en `sprint/1-catalogo-solicitud`: login local de Coordinación y CRUD Livewire del catálogo implementados, migrados y probados. El commit de la historia está pendiente de incorporarse. Próximas HU-014, HU-001 y HU-018 requieren tareas Continuum asignadas por el orquestador.
