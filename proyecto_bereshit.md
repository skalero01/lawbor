# Proyecto Bereshit: Sistema de Procesamiento de Documentos

## Objetivo del Proyecto

Bereshit es un sistema avanzado de procesamiento de documentos diseñado para automatizar el flujo de trabajo de documentos PDF, desde la carga inicial hasta la extracción de información relevante. El sistema implementa un pipeline de procesamiento que incluye:

1. **OCR (Reconocimiento Óptico de Caracteres)**: Convierte documentos escaneados o imágenes en texto buscable.
2. **Anonimización**: Identifica y oculta información sensible en los documentos.
3. **Resumen**: Genera resúmenes automáticos del contenido de los documentos.
4. **Búsqueda**: Permite buscar información específica en el texto extraído de los documentos.

Este sistema está diseñado para organizaciones que manejan grandes volúmenes de documentos y necesitan extraer, proteger y analizar la información contenida en ellos de manera eficiente.


## Estado Actual del Proyecto

### Componentes Implementados

1. **Carga de Documentos**:
   - Interfaz de usuario para subir documentos PDF (Livewire + FilePond)
   - Validación de archivos y almacenamiento seguro
   - Creación de registros en la base de datos

2. **Procesamiento OCR**:
   - Sistema de colas con Laravel Horizon (cola dedicada 'ocr' con timeout de 600s)
   - Integración con OCRmyPDF en contenedor dedicado
   - Extracción de texto y almacenamiento en la base de datos
   - Manejo de errores y reintentos
   - Visualización del texto OCR con función de copia al portapapeles

3. **Anonimización de Datos**:
   - Integración con Ollama para detección de entidades sensibles
   - Soporte para múltiples proveedores de IA (OpenAI, Ollama)
   - Detección de entidades específicas para documentos mexicanos (CURP, RFC, etc.)
   - Procesamiento asíncrono en cola dedicada 'anonymization' (timeout: 300s)
   - Almacenamiento encriptado de datos sensibles
   - Proceso de segunda pasada para mejorar la detección de entidades

4. **Análisis de IA**:
   - Procesamiento por lotes para documentos extensos
   - Análisis detallado de documentos legales con múltiples campos
   - Integración con OpenAI y Ollama como alternativas
   - Cola dedicada 'ai-analysis' (timeout: 300s)
   - Combinación inteligente de resultados parciales

5. **Configuración de Servicios de IA**:
   - Panel de administración para configurar servicios de IA
   - Soporte para múltiples configuraciones por tipo de servicio
   - Gestión de parámetros como modelo, temperatura, tokens máximos, etc.

6. **Modelos de Datos**:
   - Document: Almacena metadatos del documento y estados de procesamiento
   - DocumentText: Almacena el texto OCR original y el texto anonimizado
   - DocumentAlias: Mapea alias anonimizados a información sensible original (encriptada)
   - DocumentAnalysis: Almacena resultados de análisis de IA con diferentes tipos (kind)
   - AiServiceConfiguration: Almacena configuraciones de servicios de IA
### Flujo de Trabajo Actual

```
[Usuario] → [Subir Documento] → [Cola OCR] → [Procesamiento OCR] → [Almacenamiento de Texto] → [Cola Anonymization] → [Procesamiento Anonymization] → [Almacenamiento de Texto Anonimizado] → [Cola AI-Analysis] → [Procesamiento Análisis IA] → [Almacenamiento de Resultados]
```

### Características Implementadas

1. **Procesamiento por Lotes**:
   - División de documentos extensos en fragmentos manejables
   - Respeto de la estructura de párrafos para mantener el contexto
   - Procesamiento independiente de cada fragmento
   - Combinación inteligente de resultados parciales

2. **Análisis Detallado de Documentos Legales**:
   - Extracción de información general (resumen, ubicación)
   - Identificación de personas y roles
   - Extracción de declaraciones y testimonios
   - Identificación de leyes y reglamentos citados
   - Extracción de constancias y marcas de tiempo
   - Identificación de acuerdos, fechas y firmantes
   - Interpretación del caso desde perspectiva de IA
   - Evaluación de riesgos y limitaciones
   - Recomendaciones para diferentes partes (juez, denunciante, fiscalía)

3. **Configuración Flexible de Servicios de IA**:
   - Gestión de múltiples configuraciones por tipo de servicio
   - Selección de proveedor (OpenAI, Ollama)
   - Configuración de parámetros (modelo, temperatura, tokens máximos)
   - Interfaz de administración para gestión de configuraciones

4. **Rehidratación de Datos**:
   - Mecanismo para reemplazar alias con valores originales cuando sea necesario
   - Interfaz para visualización de datos rehidratados

## Próximos Pasos

### Pendientes de Implementación

1. **Mejoras en la Anonimización**:
   - Optimizar la detección de variaciones de nombres
   - Mejorar la consistencia en la anonimización de entidades
   - Implementar reconocimiento de entidades específicas adicionales

2. **Mejoras en el Análisis de IA**:
   - Optimizar prompts para mejorar la calidad de los resultados
   - Implementar análisis comparativo entre diferentes modelos
   - Desarrollar métricas de evaluación de calidad de análisis

3. **Interfaz de Búsqueda**:
   - Desarrollar componentes Livewire para búsqueda avanzada
   - Implementar filtros por metadatos y contenido
   - Crear visualización de resultados con resaltado

4. **Seguridad y Permisos**:
   - Implementar sistema de roles y permisos
   - Configurar acceso a documentos basado en permisos
   - Auditoría de acciones sobre documentos

### Diagrama del Flujo de Procesamiento Automático

```
┌─────────┐     ┌──────────────┐     ┌─────────────┐     ┌───────────────┐
│ Usuario │────▶│ Subir        │────▶│ Cola OCR    │────▶│ Procesamiento │
│         │     │ Documento    │     │ Redis+Horizon│     │ OCRmyPDF      │
└─────────┘     └─────┬──────┘     └─────────────┘     └───────┬───────┘
                      │                                           │
                      ▼                                           │
           ┌──────────────┐                                  │
           │  Storage      │  Disco local / MinIO                │
           └────────┬─────┘                                  │
                      │                                           │
                      │                                           │
                      └───────────────────────────────────◀──┘
                                                                 │
                                                                 ▼
                                                         ┌───────────────┐
                                                         │ Almacenamiento│
                                                         │ PostgreSQL    │
                                                         └───────┬───────┘
                                                                 │
                                                                 ▼
                    ┌──────────────┐     ┌─────────────┐     ┌───────────────┐
                    │ Documento    │◀────│ Cola        │◀────│ Procesamiento │
                    │ Anonimizado  │     │ Anonymization│     │ Ollama        │
                    └──────┬───────┘     └─────────────┘     └───────────────┘
                           │
                           ▼
┌─────────────┐     ┌──────────────┐     ┌─────────────┐     
│ Búsqueda    │◀────│ Resumen      │◀────│ Cola        │     
│ Livewire    │     │ Anonimizado  │     │ Summary     │     
└─────────────┘     └───────┬──────┘     └─────────────┘     
                           │
                           ▼
                    ┌────────────────────────────────────────────────────┐
                    │                                                        │
                    │  Opciones para generación de resúmenes:                  │
                    │                                                        │
                    │  A) GPT-4o / OpenAI API   - *sólo con datos anonimizados*  │
                    │  B) Llama 3 / Mistral     - *self-hosted*                 │
                    │                                                        │
                    └────────────────────────────────────────────────────┘
```

### Diagrama de Interacciones del Usuario

```
                    ┌──────────────────────────────────────────────────────────────┐
                    │                                                                      │
                    │                       INTERACCIONES DEL USUARIO                         │
                    │                                                                      │
                    └──────────────────────────────────────────────────────────────┘
                                                    │
                                                    ▼
                    ┌──────────────┐                 ┌──────────────┐
                    │ Listado de    │────────────────▶│ Visualización │
                    │ Documentos    │                 │ Documento     │
                    └──────────────┘                 └─────┬───────┘
                                                            │
                                                            ▼
                                                    ┌──────────────┐
                                                    │ Visualización │
                                                    │ Texto OCR     │
                                                    └─────┬───────┘
                                                            │
                                                            ▼
                                                    ┌──────────────┐
                                                    │ Copia al      │
                                                    │ Portapapeles  │
                                                    └──────────────┘
```

> **Nota importante**: Ningún dato sensible sale del sistema sin ser anonimizado previamente. La rehidratación de datos originales solo ocurre bajo permisos específicos y dentro del entorno seguro.

## Consideraciones Técnicas

1. **Escalabilidad**:
   - El sistema está diseñado para escalar horizontalmente
   - Las colas permiten distribuir la carga de trabajo
   - Los servicios en contenedores facilitan la replicación

2. **Seguridad**:
   - Anonimización de información sensible
   - Almacenamiento seguro de documentos
   - Control de acceso basado en roles

3. **Rendimiento**:
   - Procesamiento asíncrono para operaciones intensivas
   - Optimización de consultas para búsquedas rápidas
   - Almacenamiento eficiente de texto y metadatos

4. **Mantenibilidad**:
   - Código limpio y bien documentado
   - Pruebas automatizadas
   - Monitoreo de colas y procesos

## Conclusión

El proyecto Bereshit representa una solución completa para la gestión y procesamiento de documentos. Con la infraestructura básica y el procesamiento OCR ya implementados, el sistema está listo para avanzar hacia las etapas de anonimización y generación de resúmenes, completando así un flujo de trabajo integral para el manejo de documentos.

## Detalles de la Implementación

### Flujo de Procesamiento OCR

1. El usuario sube un documento PDF a través de la interfaz web
2. El componente Livewire `DocumentUploader` guarda el archivo y crea un registro en la base de datos
3. Se encola un trabajo `ProcessDocumentOcr` en la cola `ocr`
4. Laravel Horizon procesa el trabajo:
   - Ejecuta OCRmyPDF en el contenedor dedicado
   - Extrae el texto del documento
   - Guarda el texto en la tabla `document_texts`
   - Actualiza el estado del documento

### Estructura de la Base de Datos

```
documents
  ├── id
  ├── user_id
  ├── name
  ├── path
  ├── mime_type
  ├── status
  ├── error
  ├── deleted_at
  └── timestamps

document_texts
  ├── id
  ├── document_id
  ├── body (texto original extraído)
  ├── clean_body (texto anonimizado)
  ├── deleted_at
  └── timestamps

document_analysis
  ├── id
  ├── document_id
  ├── kind (summary, tags, etc.)
  ├── content (JSON)
  ├── deleted_at
  └── timestamps

document_aliases
  ├── id
  ├── document_id
  ├── entity_type (PERSON, EMAIL, LOCATION, etc.)
  ├── original_value
  ├── alias
  ├── deleted_at
  └── timestamps
```

### Tecnologías Utilizadas

- **Backend**: Laravel 12, PHP 8.4
- **Frontend**: Livewire 3, TailwindCSS, WireUI
- **Base de Datos**: PostgreSQL
- **Colas**: Redis, Laravel Horizon
- **OCR**: OCRmyPDF
- **Anonimización**: ollama
