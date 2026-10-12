# Notas de trabajo: gt-hu-017-implementacion-rubricas

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- Tras extracción local filtrada se identificaron cuatro criterios legibles de supervisor; del formato de tutor no se pudo extraer texto estructural útil. La solicitante confirmó precargar solo lo legible en ambos borradores incompletos y requerir que Coordinación complete/valide antes de publicar.

## Evidencia recopilada
- Búsqueda previa reportó patrones de Coordinator Livewire/middleware, rutas y migraciones con restricciones únicas en la aplicación Laravel.
- Inspección local sanitizada de `docs/formatos/EVAL_SUPERVISOREMPRESA.docx`: “Capacidad de aprendizaje de habilidades para el área”, “Actitud hacia el trabajo”, “Actitud y conducta” y “Responsabilidad”. No se versiona el DOCX ni se conservaron datos personales.
- Inspección estructural de `EVAL_TUTORDEPRACTICAS.docx`: no se detectó texto útil legible; no se inspeccionaron sus imágenes.

## Callejones sin salida (para no repetirlos)
- Una búsqueda delegada inicial interpretó bytes comprimidos DOCX como texto. Ignorar esa salida; solo usar la extracción XML local filtrada descrita arriba.

## Implementación y validación
- Persistencia: `rubrics`, `rubric_versions`, `rubric_criteria`; secuencias por tipo y puntero al único vigente por tipo. Restricciones únicas cubren tipo, número de versión y posición del criterio.
- Seed idempotente: supervisor v1 borrador con exactamente cuatro nombres legibles y escalas/pesos vacíos; tutor v1 borrador vacío. Ningún formato institucional fue agregado.
- El componente Livewire `/coordinacion/rubricas` valida rol en ruta y cada acción, presenta historial y vigente, permite crear/editar borradores, criterios y niveles de escala, y publicar. Publicadas no se pueden cambiar ni borrar por operaciones de modelo; cualquier edición requiere nueva versión.
- Publicar usa transacción y el mismo `RubricScoreCalculator` valida estructura, valores numéricos únicos/no negativos por escala, máximo válido y suma 100; la fórmula normaliza cada puntaje por máximo y aplica peso.
- `CoordinatorRubricManagementTest`: 7 pruebas/29 aserciones aprobadas. Se comprobó también unicidad DB de tipo/versión/posición. Pint `--test` pasó en 10 PHP. Tests ejecutados vía `docker compose run` con MySQL del Compose; `touch .env` solo dentro del contenedor evita el warning preexistente de phpunit al faltar ese archivo en la imagen.
- No se ejecutaron suite completa ni `continuum doctor`. No se hizo commit ni se cerró la tarea.
