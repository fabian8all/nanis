# Notas de trabajo: backlog-v1-1

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
-

## Evidencia recopilada
- La planeación v1.1 agrupa solicitudes de Acceso, Alumno, Coordinación,
  Supervisor, Asesor/tutor y requisitos transversales para todas las listas.
- En el backlog v1.0 los IDs `GT-HU-001` a `GT-HU-021` ya están usados
  (incluye `GT-HU-011`, excluida); el siguiente bloque disponible empieza en
  `GT-HU-022`.
- La aplicación actual ya tiene rutas y navegación separadas por rol. La
  Coordinación aterriza en el catálogo de empresas, el alumno en el catálogo
  (solo demo/local), y Supervisor/Tutor en cartas/reportes respectivamente.
- Seguimiento ya tiene búsqueda, filtros por etapa/estado y paginación; el
  botón de etapa se titula genéricamente “Abrir práctica”. El catálogo de
  Coordinación muestra el formulario en la página y la tabla completa sin
  controles de búsqueda/paginación visibles.
- En el reporte de Tutor, las observaciones por sección están siempre visibles
  junto con el comentario general.
- La gestión de rúbricas tiene ruta `coordinacion.rubrics.manage`; la solicitud
  de v1.1 es validar/corregir el enlace percibido como 404, no crear otro
  módulo.

## Callejones sin salida (para no repetirlos)
- No reutilizar IDs de historias de v1.0 ni asumir que el estado “solicitud
  en trámite” cubre también prácticas terminadas o canceladas; esos bordes se
  documentarán como preguntas por confirmar.
