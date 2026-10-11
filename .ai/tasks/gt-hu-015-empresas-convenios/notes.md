# Notas de trabajo: gt-hu-015-empresas-convenios

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
- La implementación queda en espera de que se definan autenticación y permisos de Coordinación, para no exponer la administración del catálogo sin control de acceso.

## Evidencia recopilada
- GT-HU-015: Coordinación captura nombre, dirección, teléfono, email y persona de contacto; administra altas y bajas manualmente.
- La baja oculta la empresa de nuevas solicitudes, conserva referencias existentes y no afecta prácticas iniciadas.
- Sprint 1 depende de HU-015 antes de consulta/selección por estudiante (HU-014) y solicitud (HU-001).
- Las rúbricas y documentos de solicitud nuevos modifican el plan de sprints, no los campos definidos para el catálogo.

## Callejones sin salida (para no repetirlos)
- No guardar fechas/documentos de convenio como campos del catálogo sin una decisión explícita; la coordinación mantiene manualmente el conjunto de empresas disponibles.
