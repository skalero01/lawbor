<?php

namespace Database\Seeders;

use App\Enums\AiPromptType;
use App\Enums\AiServiceType;
use App\Models\AiProvider;
use App\Models\AiPrompt;
use App\Models\AiServiceConfiguration;
use Illuminate\Database\Seeder;

class AiTablesSeeder extends Seeder
{
    public function run(): void
    {
        $openaiProvider = AiProvider::firstOrCreate(
            ['name' => 'OpenAI'],
            [
            'name' => 'OpenAI',
            'base_url' => 'https://api.openai.com/v1',
            'api_key' => null,
            'available_models' => ['gpt-3.5-turbo', 'gpt-4', 'gpt-4o'],
            'default_parameters' => [
                'temperature' => 0.7,
                'max_tokens' => 2000,
                'timeout_seconds' => 1800,
            ],
            'is_active' => true,
            'description' => 'OpenAI API provider for GPT models',
        ]);
        
        $ollamaProvider = AiProvider::firstOrCreate(
            ['name' => 'Ollama'],
            [
            'name' => 'Ollama',
            'base_url' => 'http://ollama:11434/api',
            'api_key' => null,
            'available_models' => ['llama3', 'phi'],
            'default_parameters' => [
                'temperature' => 0.7,
                'max_tokens' => 2000,
                'timeout_seconds' => 1800,
            ],
            'is_active' => true,
            'description' => 'Ollama local LLM provider',
        ]);

        $analysisFields = [
            'general_summary' => 'Descripción general breve del documento (número de caso, fecha, ubicación, partes involucradas, propósito, estado, acuerdos, límites de comunicación, uso de glosario y claridad de términos).',
            'location' => 'Lugar: Ciudad, estado, agencia (por ejemplo, Fiscalía de Delitos Sexuales).',
            'people_and_roles' => 'Personas y roles: Denunciante, agentes del Ministerio Público, secretarios, testigos, denunciado, quién da fe.',
            'statements' => 'Declaraciones/testimonios: Declaración del denunciante, eventos clave (por ejemplo, citatorio).',
            'laws_regulations' => 'Leyes/reglamentos: Leyes citadas (por ejemplo, Constitución, Código de Procedimientos Penales).',
            'records' => 'Constancias: Marcas de tiempo y propósitos (por ejemplo, comparecencia).',
            'agreements' => 'Acuerdos: Fechas, propósitos (por ejemplo, reapertura de la indagatoria), firmantes.',
            'ai_understanding' => 'Entendimiento como IA: Interpretación del caso (por ejemplo, posible delito sexual), contexto legal y reconocimiento de fragmentación del texto.',
            'diagnosis' => 'Diagnóstico: Evaluación de la naturaleza del caso, estado de la investigación, riesgos (por ejemplo, para menores) y limitaciones.',
            'suggestions_judge' => 'Sugerencias para el Juez: Ordenar diligencias, proteger a menores, supervisar al Ministerio Público.',
            'suggestions_complainant' => 'Sugerencias para el Denunciante: Buscar ayuda legal gratuita, proporcionar evidencia, asegurar comunicación.',
            'suggestions_prosecution' => 'Sugerencias para la Fiscalía: Ejecutar diligencias, proteger a víctimas, mejorar comunicación.',
            'unclear_terms' => 'Palabras no entendidas: Lista de términos poco claros con página y renglón, o indicar que no hay ninguno.',
        ];

        $anonymizationPrompt = AiPrompt::firstOrCreate(
            [
                'service_type' => AiServiceType::ANONYMIZATION->value,
                'prompt_type' => AiPromptType::STANDARD->value,
                'name' => 'Prompt de anonimización estándar'
            ],
            [
            'service_type' => AiServiceType::ANONYMIZATION->value,
            'prompt_type' => AiPromptType::STANDARD->value,
            'name' => 'Prompt de anonimización estándar',
            'content' => <<<'PROMPT'
Eres un modelo de lenguaje avanzado especializado en la anonimización precisa de documentos legales mexicanos.
Tu tarea es identificar y envolver información sensible específica ÚNICAMENTE con el formato [[TIPO_ENTIDAD:VALOR_EXACTO_ORIGINAL]].

Tipos de entidad a identificar y su formato:
1. Nombres completos de personas (incluyendo todos los apellidos) -> [[NOMBRE_COMPLETO:Valor exacto original]]
2. Direcciones postales completas (incluyendo calle, número, colonia, delegación/municipio, ciudad, estado y CÓDIGO POSTAL) -> [[DIRECCION:Valor exacto original]]
3. Identificadores únicos como CURP, RFC, números de folio, números de credencial, etc. -> [[ID:Valor exacto original]]
4. Números de teléfono -> [[TELEFONO:Valor exacto original]]
5. Direcciones de correo electrónico -> [[CORREO_ELECTRONICO:Valor exacto original]]
6. Nombres de centros educativos -> [[CENTRO_EDUCATIVO:Valor exacto original]]
7. Placas de vehículos -> [[PLACA_VEHICULO:Valor exacto original]]

REGLAS CRÍTICAS:
- EL VALOR_EXACTO_ORIGINAL debe ser la cadena de texto EXACTA Y COMPLETA tal como aparece en el documento.
- Para direcciones, ASEGÚRATE DE INCLUIR EL CÓDIGO POSTAL si está presente.
- NO ANIDES etiquetas. Por ejemplo, NUNCA hagas esto: [[ID:Texto con [[OTRA_COSA:interno]] ]]. Cada entidad es una etiqueta independiente.
- Conserva intacta toda la estructura del texto original: formato, espaciado, mayúsculas/minúsculas, saltos de línea, puntuación.
- NO MODIFIQUES, abrevies, inventes ni alteres el contenido del texto. Tu única tarea es envolver la información sensible.
- Fechas, horas, términos jurídicos, nombres de instituciones públicas (que no sean centros educativos), y otra información no listada arriba, NO deben ser modificadas ni etiquetadas.

Ejemplos:
- Original: Juan Alberto Pérez López vive en Calle Reforma 123, Colonia Centro, CDMX, C.P. 06000 y su RFC es PELJ800101XXX.
- Anonymizado: [[NOMBRE_COMPLETO:Juan Alberto Pérez López]] vive en [[DIRECCION:Calle Reforma 123, Colonia Centro, CDMX, C.P. 06000]] y su RFC es [[ID:PELJ800101XXX]].

Texto a procesar:
{{TEXT}}
PROMPT,
            'analysis_fields' => null,
            'is_default' => true,
            'is_active' => true,
            'description' => 'Prompt estándar para anonimización de documentos legales',
        ]);

        $analysisPrompt = AiPrompt::firstOrCreate(
            [
                'service_type' => AiServiceType::ANALYSIS->value,
                'prompt_type' => AiPromptType::STANDARD->value,
                'name' => 'Prompt de análisis estándar'
            ],
            [
            'service_type' => AiServiceType::ANALYSIS->value,
            'prompt_type' => AiPromptType::STANDARD->value,
            'name' => 'Prompt de análisis estándar',
            'content' => <<<'PROMPT'
Analiza el siguiente documento de fiscalía y extrae la información solicitada.

DOCUMENTO:
{{TEXT}}

Por favor, analiza el documento y extrae la información para cada uno de los siguientes campos. Responde con un JSON que contenga SOLO la información extraída, NO repitas las descripciones de los campos:

{
{{FIELDS}}
}

Para cada campo, proporciona información específica y detallada basada en el contenido del documento. Si no hay información disponible para algún campo, indica "No se menciona en el documento".

Responde SOLO con el JSON, sin texto adicional ni explicaciones.
PROMPT,
            'analysis_fields' => $analysisFields,
            'is_default' => true,
            'is_active' => true,
            'description' => 'Prompt estándar para análisis de documentos legales',
        ]);

        $chunkAnalysisPrompt = AiPrompt::firstOrCreate(
            [
                'service_type' => AiServiceType::ANALYSIS->value,
                'prompt_type' => AiPromptType::CHUNK->value,
                'name' => 'Prompt para análisis de fragmentos'
            ],
            [
            'service_type' => AiServiceType::ANALYSIS->value,
            'prompt_type' => AiPromptType::CHUNK->value,
            'name' => 'Prompt para análisis de fragmentos',
            'content' => <<<'PROMPT'
Analiza el siguiente fragmento ({{CHUNK_NUMBER}} de {{TOTAL_CHUNKS}}) de un documento de fiscalía y extrae la información solicitada.

FRAGMENTO DE DOCUMENTO:
{{TEXT}}

Por favor, analiza el fragmento y extrae la información para cada uno de los siguientes campos. Responde con un JSON que contenga SOLO la información extraída, NO repitas las descripciones de los campos:

{
{{FIELDS}}
}

Para cada campo, proporciona información específica y detallada basada en el contenido del fragmento. Si no hay información disponible para algún campo, indica "No se menciona en este fragmento".

Ten en cuenta que este es solo un fragmento del documento completo. Enfócate en extraer la información relevante de este fragmento específico.
Responde SOLO con el JSON, sin texto adicional ni explicaciones.
PROMPT,
            'analysis_fields' => $analysisFields,
            'is_default' => false,
            'is_active' => true,
            'description' => 'Prompt para analizar fragmentos de documentos legales',
        ]);

        $fieldCombinationPrompt = AiPrompt::firstOrCreate(
            [
                'service_type' => AiServiceType::ANALYSIS->value,
                'prompt_type' => AiPromptType::COMBINATION->value,
                'name' => 'Prompt para combinar resultados de campo'
            ],
            [
            'service_type' => AiServiceType::ANALYSIS->value,
            'prompt_type' => AiPromptType::COMBINATION->value,
            'name' => 'Prompt para combinar resultados de campo',
            'content' => <<<'PROMPT'
Combina los siguientes resultados parciales para el campo "{{FIELD}}" de un documento de fiscalía.

DESCRIPCIÓN DEL CAMPO:
{{DESCRIPTION}}

RESULTADOS PARCIALES:
{{FIELD_VALUES}}

Por favor, analiza estos resultados parciales y crea un análisis completo y coherente para este campo específico.
Responde con un JSON que contenga SOLO la información combinada para este campo:

{
    "{{FIELD}}": "Información combinada y coherente"
}

Al combinar la información:
1. Integra toda la información relevante de los resultados parciales
2. Elimina duplicados y contradicciones
3. Crea un análisis coherente y completo
4. Proporciona información específica y detallada
5. Si no hay información disponible, indica "No se menciona en el documento"

Responde SOLO con el JSON, sin texto adicional ni explicaciones.
PROMPT,
            'analysis_fields' => null,
            'is_default' => false,
            'is_active' => true,
            'description' => 'Prompt para combinar resultados parciales de un campo específico',
        ]);

        AiServiceConfiguration::firstOrCreate(
            [
                'provider_id' => $openaiProvider->id,
                'service_type' => AiServiceType::ANONYMIZATION->value,
                'name' => 'OpenAI GPT-4o para Anonimización'
            ],
            [
            'provider_id' => $openaiProvider->id,
            'service_type' => AiServiceType::ANONYMIZATION->value,
            'name' => 'OpenAI GPT-4o para Anonimización',
            'model' => 'gpt-4o',
            'timeout_seconds' => 1800,
            'max_chars_per_batch' => 2000,
            'temperature' => 0.0,
            'max_tokens' => 4000,
            'service_parameters' => [
                'prompt_id' => $anonymizationPrompt->id,
            ],
            'is_active' => true,
            'is_default' => true,
            'description' => 'Configuración por defecto para anonimización con OpenAI GPT-4o',
        ]);
        
        AiServiceConfiguration::firstOrCreate(
            [
                'provider_id' => $openaiProvider->id,
                'service_type' => AiServiceType::ANALYSIS->value,
                'name' => 'OpenAI GPT-4o para Análisis'
            ],
            [
            'provider_id' => $openaiProvider->id,
            'service_type' => AiServiceType::ANALYSIS->value,
            'name' => 'OpenAI GPT-4o para Análisis',
            'model' => 'gpt-4o',
            'timeout_seconds' => 1800,
            'max_chars_per_batch' => 6000,
            'temperature' => 0.7,
            'max_tokens' => 4000,
            'service_parameters' => [
                'standard_prompt_id' => $analysisPrompt->id,
                'chunk_prompt_id' => $chunkAnalysisPrompt->id,
                'combination_prompt_id' => $fieldCombinationPrompt->id,
            ],
            'is_active' => true,
            'is_default' => true,
            'description' => 'Configuración por defecto para análisis con OpenAI GPT-4o',
        ]);
        
        AiServiceConfiguration::firstOrCreate(
            [
                'provider_id' => $ollamaProvider->id,
                'service_type' => AiServiceType::ANONYMIZATION->value,
                'name' => 'Ollama Llama3 para Anonimización'
            ],
            [
            'provider_id' => $ollamaProvider->id,
            'service_type' => AiServiceType::ANONYMIZATION->value,
            'name' => 'Ollama Llama3 para Anonimización',
            'model' => 'llama3',
            'timeout_seconds' => 1800,
            'max_chars_per_batch' => 1500,
            'temperature' => 0.0,
            'max_tokens' => 4000,
            'service_parameters' => [
                'prompt_id' => $anonymizationPrompt->id,
            ],
            'is_active' => true,
            'is_default' => false,
            'description' => 'Configuración para anonimización con Ollama Llama3',
        ]);
        
        AiServiceConfiguration::firstOrCreate(
            [
                'provider_id' => $ollamaProvider->id,
                'service_type' => AiServiceType::ANALYSIS->value,
                'name' => 'Ollama Llama3 para Análisis'
            ],
            [
            'provider_id' => $ollamaProvider->id,
            'service_type' => AiServiceType::ANALYSIS->value,
            'name' => 'Ollama Llama3 para Análisis',
            'model' => 'llama3',
            'timeout_seconds' => 1800,
            'max_chars_per_batch' => 4000,
            'temperature' => 0.7,
            'max_tokens' => 4000,
            'service_parameters' => [
                'standard_prompt_id' => $analysisPrompt->id,
                'chunk_prompt_id' => $chunkAnalysisPrompt->id,
                'combination_prompt_id' => $fieldCombinationPrompt->id,
            ],
            'is_active' => true,
            'is_default' => false,
            'description' => 'Configuración para análisis con Ollama Llama3',
        ]);
    }
}
