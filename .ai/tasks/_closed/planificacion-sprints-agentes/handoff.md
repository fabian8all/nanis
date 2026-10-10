# Handoff: planificacion-sprints-agentes

## Objetivo y resultado

Se organizó el backlog candidato en cinco sprints por resultado y se propuso
apoyo por roles/agentes, con prioridades P0/P1/P2 aceptadas como base. El plan
se mantiene sin fechas, duración ni estimaciones comprometidas. Las historias
y criterios aún abiertos se trasladan como pendientes explícitos del backlog,
no como criterios ya aprobados.

## Decisiones confirmadas

- El prototipo cubre prácticas operativas y de mandos medios de Gestión
  Turística, con conclusión a las 240 horas, una evaluación terminal y un
  reporte final, sin actividades intermedias.
- La empresa/supervisor aplica la evaluación terminal. Coordinación de
  prácticas definirá y aprobará la rúbrica.
- El tutor/asesor académico revisa el reporte final y puede devolverlo para
  corrección hasta su aprobación.
- La carta de término registrada acredita las 240 horas; no habrá contador
  independiente de horas en la plataforma.
- Se incluye una encuesta de satisfacción de la empresa, separada de la
  evaluación terminal. La responde el supervisor directo cuando coordinación
  la solicite, sin fecha fija; solo coordinación consulta las respuestas.
- La secuencia de cinco sprints, el esquema de apoyo por roles y P0/P1/P2 se
  aceptaron como base, no como compromisos de calendario o capacidad.
- Para el trabajo futuro, cada historia tendrá una tarea Continuum asignada
  por el agente orquestador; habrá una rama por sprint desde `develop`, commit
  al cerrar cada tarea de historia, merge del sprint a `develop` y merge final
  de `develop` a `main`. El protocolo persistente está en
  `AI_COLLABORATION.md` §2.1.

## Pendientes del producto

- Acordar criterios, formato y acceso de la rúbrica; criterios/estados y
  documentos del reporte final; formato y validación de la carta de término.
- Refinar el cuestionario de satisfacción, su responsable, canal y
  resguardo. El canal y quién diseña/aprueba el cuestionario siguen sin
  definirse.
- Refinar permisos, documentos, flujo, datos personales y criterios de
  aceptación antes de dimensionar historias.

## Archivos principales

- `docs/sprints/plan-sprints-candidato.md`
- `docs/backlog/candidatos-iniciales.md`
- `docs/casos-de-uso/flujo-candidato-practica.md`
- `docs/requerimientos/vision-inicial.md`
- `.ai/state/topics/resumen.md` y `.ai/state/topics/pendientes.md`
- `AI_COLLABORATION.md`

## Validación y siguiente paso

Las modificaciones son documentales; `git diff --check` debe ejecutarse antes
del commit. El entorno Windows no expone `python3` directamente; ejecutar las
validaciones de Continuum dentro de WSL. Para comenzar desarrollo, confirmar
que `develop` exista y abrir una tarea Continuum por cada historia del primer
sprint según `AI_COLLABORATION.md` §2.1.
