<?php

namespace App\Services\DocumentAnalysis;

use App\Models\AiServiceConfiguration;
use App\Models\Document;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

/**
 * Handles combining results from multiple document chunks
 */
class ResultCombiner
{
    private AiServiceConfiguration $config;
    private PromptManager $promptManager;
    private LlmClient $llmClient;
    private ResultProcessor $resultProcessor;

    public function __construct(
        AiServiceConfiguration $config,
        PromptManager $promptManager,
        LlmClient $llmClient,
        ResultProcessor $resultProcessor
    ) {
        $this->config = $config;
        $this->promptManager = $promptManager;
        $this->llmClient = $llmClient;
        $this->resultProcessor = $resultProcessor;
    }

    /**
     * Combine results from multiple chunks
     */
    public function combineChunkResults(Document $document, array $chunkResults): array
    {
        $this->validateChunkResults($document, $chunkResults);
        
        if (count($chunkResults) === 1) {
            return $chunkResults[0];
        }
        
        $allFieldsWithValues = $this->extractFieldsWithValues($chunkResults);
        
        if (empty($allFieldsWithValues)) {
            throw new RuntimeException('No valid combined data for document ID: ' . $document->id);
        }
        
        return $this->combineFieldValues($document, $allFieldsWithValues);
    }
    
    /**
     * Validate that chunk results are valid
     */
    private function validateChunkResults(Document $document, array $chunkResults): void
    {
        if (empty($chunkResults)) {
            throw new InvalidArgumentException('No chunk results for document ID: ' . $document->id);
        }
        
        foreach ($chunkResults as $index => $result) {
            if (!is_array($result) || empty($result)) {
                throw new InvalidArgumentException(
                    "Invalid result at position {$index} for document ID: {$document->id}"
                );
            }
        }
    }
    
    /**
     * Extract all unique fields and their values from chunk results
     */
    public function extractFieldsWithValues(array $chunkResults): array
    {
        $allFields = [];
        foreach ($chunkResults as $result) {
            $allFields = array_merge($allFields, array_keys($result));
        }
        $allFields = array_unique($allFields);
        
        $fieldValues = [];
        foreach ($allFields as $field) {
            $values = [];
            foreach ($chunkResults as $result) {
                if (isset($result[$field]) && !empty($result[$field])) {
                    $values[] = $result[$field];
                }
            }
            
            if (!empty($values)) {
                $fieldValues[$field] = $values;
            }
        }
        
        return $fieldValues;
    }
    
    /**
     * Combine values for each field into a single coherent result
     */
    private function combineFieldValues(Document $document, array $fieldValues): array
    {
        $finalResults = [];
        $errors = [];

        foreach ($fieldValues as $field => $values) {
            try {
                $finalResults[$field] = $this->combineFieldData($field, $values);
            } catch (Throwable $e) {
                throw new RuntimeException("Failed to combine field '{$field}' for document ID: {$document->id}", 0, $e);
            }
        }

        return $finalResults;
    }
    
    /**
     * Combine multiple values for a single field
     */
    private function combineFieldData(string $field, array $fieldValues): string
    {
        $prompt = $this->promptManager->prepareFieldCombinationPrompt($field, $fieldValues);
        $response = $this->llmClient->sendPromptToLlm($prompt);
        
        // Extract structured data first
        $jsonData = $this->resultProcessor->extractJsonFromMarkdown($response);
        if (!empty($jsonData) && isset($jsonData[$field])) {
            $fieldData = $jsonData[$field];
            if (is_array($fieldData)) {
                return json_encode($fieldData, JSON_UNESCAPED_UNICODE);
            }
            return $fieldData;
        }
        
        // Try direct JSON decode
        try {
            $fieldResult = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
            if (isset($fieldResult[$field])) {
                $fieldData = $fieldResult[$field];
                if (is_array($fieldData)) {
                    return json_encode($fieldData, JSON_UNESCAPED_UNICODE);
                }
                return $fieldData;
            }
        } catch (\JsonException $e) {
            // Continue with other approaches
        }
        
        // If we couldn't extract structured data, use full response
        if (!empty(trim($response))) {
            return $response;
        }
        
        // Fall back to first value if all else fails
        if (!empty($fieldValues[0])) {
            return $fieldValues[0];
        }
        
        throw new RuntimeException("Could not extract or generate value for field '{$field}'");
    }
}
