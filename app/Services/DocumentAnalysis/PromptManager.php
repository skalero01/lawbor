<?php

namespace App\Services\DocumentAnalysis;

use App\Models\AiServiceConfiguration;
use App\Enums\AiPromptType;
use RuntimeException;

/**
 * Manages the creation and formatting of prompts for different analysis scenarios
 */
class PromptManager
{
    private AiServiceConfiguration $config;

    public function __construct(AiServiceConfiguration $config)
    {
        $this->config = $config;
        $this->validateRequiredPrompts();
    }
    
    /**
     * Verify all required prompt types are available
     */
    private function validateRequiredPrompts(): void
    {
        $requiredPromptTypes = [
            AiPromptType::STANDARD,
            AiPromptType::CHUNK,
            AiPromptType::COMBINATION
        ];
        
        $missingPrompts = [];
        
        foreach ($requiredPromptTypes as $promptType) {
            if (!$this->config->getPrompt($promptType)) {
                $missingPrompts[] = $promptType->value;
            }
        }
        
        if (!empty($missingPrompts)) {
            throw new RuntimeException('Missing required prompts: ' . implode(', ', $missingPrompts));
        }
    }

    /**
     * Create a standard prompt for complete document analysis
     */
    public function prepareStandardPrompt(string $text): string
    {
        $promptObj = $this->config->getPrompt(AiPromptType::STANDARD);
        
        if (!$promptObj || empty($promptObj->content)) {
            throw new RuntimeException('Missing STANDARD prompt template');
        }
        
        return strtr($promptObj->content, [
            '{{TEXT}}' => $text,
            '{{FIELDS}}' => $this->getFieldsJson($promptObj->analysis_fields)
        ]);
    }

    /**
     * Create a prompt for a specific chunk of text
     */
    public function prepareChunkPrompt(string $text, int $chunkNumber, int $totalChunks): string
    {
        $promptObj = $this->config->getPrompt(AiPromptType::CHUNK);
        
        if (!$promptObj || empty($promptObj->content)) {
            throw new RuntimeException('Missing CHUNK prompt template');
        }
        
        $replacements = [
            '{{TEXT}}' => $text,
            '{{FIELDS}}' => $this->getFieldsJson($promptObj->analysis_fields),
            '{{CHUNK_NUMBER}}' => (string)$chunkNumber,
            '{{TOTAL_CHUNKS}}' => (string)$totalChunks,
        ];
        
        return strtr($promptObj->content, $replacements);
    }

    /**
     * Create a prompt for combining multiple field values
     */
    public function prepareFieldCombinationPrompt(string $field, array $fieldValues): string
    {
        $promptObj = $this->config->getPrompt(AiPromptType::COMBINATION);
        
        if (!$promptObj || empty($promptObj->content)) {
            throw new RuntimeException('Missing COMBINATION prompt template');
        }
        
        $description = $promptObj->analysis_fields[$field] ?? "Information about $field";
        $fieldValuesText = $this->formatFieldValues($fieldValues);
        
        $replacements = [
            '{{FIELD}}' => $field,
            '{{DESCRIPTION}}' => $description,
            '{{FIELD_VALUES}}' => $fieldValuesText,
        ];
        
        return strtr($promptObj->content, $replacements);
    }
    
    /**
     * Format field values for the combination prompt
     */
    private function formatFieldValues(array $fieldValues): string
    {
        $formattedText = '';
        foreach ($fieldValues as $index => $value) {
            $formattedValue = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
            $formattedText .= "Chunk " . ($index + 1) . ": " . $formattedValue . "\n\n";
        }
        return $formattedText;
    }
    
    /**
     * Generate JSON representation of analysis fields
     */
    private function getFieldsJson($analysisFields): string
    {
        if (empty($analysisFields)) {
            return '';
        }
        
        return implode(",\n", array_map(
            fn($key) => "\"$key\": \"{$analysisFields[$key]}\"",
            array_keys($analysisFields)
        ));
    }
}
