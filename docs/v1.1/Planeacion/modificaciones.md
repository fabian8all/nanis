# Modificaciones para la versión 1.1

Este documento organiza las solicitudes para la siguiente versión. Servirá
como entrada para refinar el backlog; no establece prioridades, estimaciones
ni fechas de entrega.

## Acceso

- Cambiar el texto del botón **Acceder como alumno de prueba** por
  **Acceso federado UCOL** y colocarlo debajo del botón **Iniciar sesión**.

## Alumno

- Si todavía no existe una solicitud, permitir el acceso al catálogo de
  empresas con convenio. Si ya hay una solicitud elaborada o la práctica está
  en curso, dirigir al alumno directamente a **Seguimiento**.
- Agregar a la barra de navegación un enlace al catálogo de empresas y
  deshabilitar la selección cuando haya una solicitud en trámite.
- Corregir el error `403 - Forbidden` al consultar la solicitud.
- En el expediente, ordenar los documentos según el proceso:
  1. Carta de presentación.
  2. Carta de aceptación.
  3. Requisitos de cierre.
  4. Reporte final aprobado.
  5. Evaluaciones.
  6. Carta de término.
- Cuando un documento no exista, ocultar el botón **Consultar** o mostrarlo
  deshabilitado para evitar enlaces rotos.

## Coordinación

- Al ingresar, dirigir a **Seguimiento**.
- Agregar a la barra superior un enlace al catálogo de empresas.
- Agrupar en un menú desplegable **Documentos** los enlaces a Solicitudes,
  Aceptaciones, Cartas de término, Planes, Reportes, Evaluaciones de tutor,
  Evaluaciones de supervisor y Encuestas.
- Corregir el enlace **Rúbricas**, que actualmente muestra `404 - Not Found`.
- En el catálogo de empresas, ocultar el formulario hasta que se seleccione
  **Agregar nueva empresa** o se edite una empresa. Añadir búsqueda por nombre
  y paginación.
- En **Seguimiento**, describir con claridad la acción y el documento de cada
  botón. Por ejemplo, cambiar **Abrir práctica** por **Revisar solicitud**
  cuando el botón lleve a la revisión de la solicitud.

## Supervisor

- Agrupar en un menú desplegable **Documentos** los enlaces a Cartas
  pendientes, Planes de trabajo, Evaluaciones y Encuestas.
- En la etapa de aceptación, ofrecer acceso directo para consultar la carta
  de presentación y cargar la carta de aceptación, sin pasar por el catálogo
  de cartas pendientes.
- Al volver desde la consulta de la carta de presentación, regresar a
  **Seguimiento** y no al expediente, que actualmente produce `403 - Forbidden`.
- Traducir al español los títulos automáticos que actualmente muestran estados
  internos en inglés, como `Versión 1 - pending_supervisor_review`.

## Asesor o tutor

- En el reporte final, ocultar inicialmente las observaciones particulares.
  Añadir un botón junto a cada campo para mostrar su área de observación y
  dejar visible el campo **Comentario general**.

## Todas las listas

- Ordenar por fecha de modificación, mostrando primero el cambio más reciente.
- Añadir búsqueda y paginación donde todavía no estén disponibles.
