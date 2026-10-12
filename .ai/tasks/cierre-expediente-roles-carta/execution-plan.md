# Plan de ejecución: cierre-expediente-roles-carta

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Confirmar el flujo de carta: Coordinación genera/imprime, 240 horas, firma/sello receptor, emisión cierra.
- [x] Paso 2 — Agregar registro de emisión y requisitos transaccionales de cierre; crear vista imprimible y cola de Coordinación.
- [x] Paso 3 — Implementar autorización completa/restringida por rol y enlaces a documentos autorizados.
- [x] Paso 4 — Habilitar consulta de la propia respuesta final de encuesta al supervisor.
- [x] Paso 5 — Actualizar y ejecutar pruebas dirigidas y suite completa; aplicar Pint.
- [x] Paso 6 — Actualizar handoffs/temas Continuum y cerrar la tarea; dejar la validación humana y carga de datos diferidas.

## Estado actual
Implementación terminada en el worktree `develop`, sin commit ni merge a
`main`. Quedan para otra etapa la validación humana/institucional y carga de
datos de prueba.
