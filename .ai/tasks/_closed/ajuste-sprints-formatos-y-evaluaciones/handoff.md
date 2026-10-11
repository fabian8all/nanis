# Handoff: ajuste-sprints-formatos-y-evaluaciones

## Objetivo y resultado

Se revisaron los formatos entregados en `docs/formatos/` y se ajustaron el
backlog, la visión, el flujo de usuario y la propuesta de sprints. Sprint 1
incorpora aceptación e impresión de la carta de no responsiva; Sprint 4
incorpora evaluaciones independientes de supervisor y tutor, más la
administración de ambas rúbricas por Coordinación.

## Decisiones confirmadas

- Al cierre de las 240 horas habrá una evaluación del supervisor y otra del
  tutor; no habrá evaluaciones ni reportes intermedios.
- El formato del tutor incluye evaluación del reporte final y conserva el
  ciclo de correcciones hasta su aprobación.
- Coordinación podrá modificar ambas rúbricas. Cada evaluación conservará la
  versión de rúbrica con la que fue contestada.
- Durante la solicitud, el alumno acepta e imprime la carta de no responsiva,
  la firma físicamente y el sistema no conserva copia firmada.
- El catálogo muestra nombre, dirección, teléfono, email y persona de
  contacto. Coordinación hace altas y bajas manuales; una baja oculta a la
  empresa de nuevas solicitudes y conserva referencias existentes.
- `docs/sprints/plan-sprints-candidato.md` sigue siendo una propuesta por
  resultados, sin fechas, duración ni estimaciones comprometidas.

## Archivos actualizados

- `docs/backlog/candidatos-iniciales.md`: GT-HU-016 evaluación del tutor,
  GT-HU-017 administración versionada de rúbricas y GT-HU-018 carta de no
  responsiva.
- `docs/sprints/plan-sprints-candidato.md`: Sprint 1 y Sprint 4, dependencias
  y prioridades.
- `docs/casos-de-uso/flujo-candidato-practica.md` y
  `docs/requerimientos/vision-inicial.md`.
- `.ai/state/topics/resumen.md`, `.ai/state/topics/pendientes.md` y
  `.ai/HANDOFF.md`.
- `.ai/tasks/gt-hu-015-empresas-convenios/`: campos y regla de baja
  actualizados; aún no hay código de producto.

## Pendientes de producto

- Definir los elementos que Coordinación puede cambiar en cada rúbrica y el
  proceso de publicación; acordar escala, captura, estados y permisos para
  ambas evaluaciones.
- Refinar criterios de la solicitud y otras reglas de acceso antes de
  dimensionar las historias.
- Los formatos de imagen incluyen ejemplos completados con datos personales.
  No versionar ni redistribuir esos originales sin anonimización; los archivos
  se dejaron fuera del commit.

## Validación y siguiente paso

Cambios documentales, sin implementación de producto. `tools/continuum doctor`
y `git diff --check` se ejecutan antes del commit. Continuar con
`gt-hu-015-empresas-convenios` en `sprint/1-catalogo-solicitud`; el acceso
exclusivo de Coordinación a las pantallas de catálogo requiere definir
autenticación/permisos antes de exponer la administración.
