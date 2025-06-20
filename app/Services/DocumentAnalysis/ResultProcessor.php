<?php

namespace App\Services\DocumentAnalysis;

use JsonException;
use RuntimeException;

/**
 * Processes and validates results from LLM responses
 */
class ResultProcessor
{
    /**
     * Extract structured data from LLM response
     */
    public function validateAnalysisData($data): array
    {
        if (is_array($data)) {
            return $this->filterEmptyValues($data);
        }
        
        if (is_string($data)) {
            $jsonData = $this->extractJsonFromMarkdown($data);
            if (!empty($jsonData)) {
                return $jsonData;
            }
            
            $textValue = trim($data);
            if (!empty($textValue)) {
                return ['text' => $textValue];
            }
        }
        
        return [];
    }
    
    /**
     * Extract JSON from markdown formatted text
     */
    public function extractJsonFromMarkdown(string $text): array
    {
        // Extract JSON from code blocks (```json ... ```) 
        if (preg_match('/```(?:json)?\s*(.+?)```/s', $text, $matches)) {
            try {
                $jsonData = json_decode(trim($matches[1]), true, 512, JSON_THROW_ON_ERROR);
                if (is_array($jsonData)) {
                    return $this->filterEmptyValues($jsonData);
                }
            } catch (JsonException $e) {
                throw new RuntimeException("Failed to decode JSON response: " . $e->getMessage());
            }
        }
        
        // Try to decode JSON from the entire response
        try {
            $jsonData = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
            if (is_array($jsonData)) {
                return $this->filterEmptyValues($jsonData);
            }
        } catch (JsonException $e) {
            // Silently fail - we'll try other approaches
        }
        
        return [];
    }
    
    /**
     * Filter out empty values from results
     */
    public function filterEmptyValues(array $data): array
    {
        return array_filter($data, function ($value) {
            if (is_array($value)) {
                $value = $this->filterEmptyValues($value);
            }
            return !empty($value) || $value === 0 || $value === '0' || $value === false;
        });
    }
    
    /**
     * Encode data to JSON for storage
     */
    public function encodeToJson(array $data): string
    {
        try {
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            if (!$json) {
                throw new RuntimeException('JSON encoding returned empty string');
            }
            return $json;
        } catch (\Throwable $e) {
            throw new RuntimeException('Failed to encode analysis data to JSON: ' . $e->getMessage());
        }
    }
}
