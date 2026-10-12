# Pendientes

- El catálogo muestra nombre, dirección, teléfono, email y persona de contacto; Coordinación da altas/bajas manualmente. La baja oculta a la empresa en nuevas solicitudes, pero conserva referencias existentes y no afecta prácticas iniciadas.
- Conciliar canales del prototipo frente a Drive/correo/Classroom/papel descritos en el procedimiento institucional 2025.
- HU-006, HU-007, HU-016 y HU-017 están implementadas y desplegadas en Sprint 4. La rúbrica de Tutor publicada en desarrollo es temporal/ficticia para demostración; falta que Coordinación publique las rúbricas institucionales. La rúbrica Supervisor continúa como borrador, por lo que HU-006 no inicia aún en demo. La práctica sintética recorre aceptación, plan y reporte aprobados.
- Sprint 5 HU-008/009/010/012/013 está integrado en `develop`; las migraciones 000015/000016 están aplicadas en MySQL local. GitHub y `origin/HEAD` usan `develop` como rama predeterminada. La carta se rediseñó para emisión imprimible por Coordinación (240 horas); la migración aditiva 000017 registra la emisión y el cierre. Los cambios actuales están sin commit en el worktree `develop`.
- HU-012 permite expediente completo a Coordinación, alumno propietario y Tutor asignado; Supervisor asignado solo ve presentación, aceptación, reporte aprobado y evaluación propia. HU-013 permite al Supervisor consultar solo su propia respuesta y a Coordinación todos los resultados. Pruebas de app pasan; falta validación humana/institucional antes de integrar `develop` a `main`.
- Validar permisos/tareas de coordinador, asesor, alumno y empresa, además de estados/plazos.
- El NSS (11 dígitos) y número de cuenta (8 dígitos) son obligatorios y cifrados en el expediente: alumno propietario y Coordinación acceden al expediente; un supervisor solo los ve dentro de la carta oficial emitida para su propia empresa.
- Revisar con la autora la observación de R5 sobre el enfoque de Capacidades Humanas y definir su impacto en objetivos/indicadores.
- Confirmar privacidad, acceso, retención, uso de indicadores, cargas de trabajo y participación de los actores; los documentos los plantean como criterios, no como políticas ya acordadas.
- Refinar criterios de aceptación/dependencias antes de dimensionar historias; la persona solicitante aceptó como base los grupos P0/P1/P2 de `docs/sprints/plan-sprints-candidato.md`.
- Carga de datos de prueba diferida hasta que la persona solicitante la pida.
- No fijar duración/fechas: la persona solicitante indicó no establecer duración de sprint por ahora.

Alcance confirmado del primer prototipo: Gestión Turística solamente. Backlog candidato: `docs/backlog/candidatos-iniciales.md`.

Seguimiento detallado: `.ai/tasks/inicio-requerimientos-backlog/`.
