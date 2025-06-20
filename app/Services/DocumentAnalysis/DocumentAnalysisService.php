<?php

namespace App\Services\DocumentAnalysis;

use App\Enums\AiPromptType;
use App\Enums\AiServiceType;
use App\Models\Document;
use App\Models\AiServiceConfiguration;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

/**
 * Main service for document analysis
 * Coordinates the process of analyzing documents using various specialized components
 */
class DocumentAnalysisService
{
    private AiServiceConfiguration $config;
    private TextChunkManager $textChunkManager;
    private PromptManager $promptManager;
    private LlmClient $llmClient;
    private ResultProcessor $resultProcessor;
    private ResultCombiner $resultCombiner;

    /**
     * Initialize the service with necessary dependencies
     */
    public function __construct()
    {
        $config = AiServiceConfiguration::default(AiServiceType::ANALYSIS)->first();
        
        if (!$config) {
            throw new RuntimeException('No active configuration found for analysis service');
        }
        
        $this->config = $config;
        $this->textChunkManager = new TextChunkManager($this->config->max_chars_per_batch ?: 6000);
        $this->promptManager = new PromptManager($config);
        $this->llmClient = new LlmClient($config);
        $this->resultProcessor = new ResultProcessor();
        $this->resultCombiner = new ResultCombiner(
            $config, 
            $this->promptManager, 
            $this->llmClient, 
            $this->resultProcessor
        );
    }

    /**
     * Analyze a document, automatically handling chunking for large documents
     */
    public function analyzeDocument(Document $document, string $anonymizedText): bool
    {
        $this->validateInput($document, $anonymizedText);
        
        // Clear previous analysis results
        $document->analysis()->delete();
        
        // Split text into processable chunks
        $chunks = $this->textChunkManager->splitTextIntoChunks($anonymizedText);
        
        if (empty($chunks)) {
            throw new RuntimeException('Failed to split text for document ID: ' . $document->id);
        }
        
        // Choose processing approach based on number of chunks
        return count($chunks) === 1
            ? $this->processSingleChunk($document, $chunks[0])
            : $this->processMultiChunkDocument($document, $chunks);
    }
    
    /**
     * Validate input document and text
     */
    private function validateInput(Document $document, string $anonymizedText): void
    {
        if (!$document || !$document->exists) {
            throw new InvalidArgumentException('Invalid document provided');
        }
        
        if (empty($anonymizedText)) {
            throw new InvalidArgumentException('Empty text for document ID: ' . $document->id);
        }
    }

    /**
     * Process a document that fits in a single chunk
     */
    private function processSingleChunk(Document $document, string $text): bool
    {
        try {
            // Prepare and send prompt to LLM
            $prompt = $this->promptManager->prepareStandardPrompt($text);
            $response = $this->llmClient->sendPromptToLlm($prompt);
            
            // Process and validate the response
            $analysisData = $this->resultProcessor->validateAnalysisData($response);
            
            if (empty($analysisData)) {
                throw new RuntimeException('Failed to extract valid data from response for document ID: ' . $document->id);
            }
            
            // Save the analysis results
            return $this->saveAnalysisResults($document, $analysisData);
            
        } catch (Throwable $e) {
            throw new RuntimeException('Error processing single chunk for document ID: ' . $document->id, 0, $e);
        }
    }

    /**
     * Process a document that requires multiple chunks
     */
    private function processMultiChunkDocument(Document $document, array $chunks): bool
    {
        try {
            // Process all chunks individually
            $chunkResults = [];
            $totalChunks = count($chunks);
            
            foreach ($chunks as $index => $chunk) {
                $chunkNumber = $index + 1;
                
                // Prepare and send prompt for this chunk
                $prompt = $this->promptManager->prepareChunkPrompt($chunk, $chunkNumber, $totalChunks);
                $response = $this->llmClient->sendPromptToLlm($prompt);
                
                // Process and validate chunk response
                $chunkData = $this->resultProcessor->validateAnalysisData($response);
                
                if (empty($chunkData)) {
                    throw new RuntimeException(
                        "Invalid analysis data for chunk {$chunkNumber}/{$totalChunks} of document ID: {$document->id}"
                    );
                }
                
                $chunkResults[] = $chunkData;
            }
            
            // Combine results from all chunks
            $combinedResults = $this->resultCombiner->combineChunkResults($document, $chunkResults);
            
            if (empty($combinedResults)) {
                throw new RuntimeException('Failed to combine chunk results for document ID: ' . $document->id);
            }
            
            // Save the combined results
            return $this->saveAnalysisResults($document, $combinedResults);
            
        } catch (Throwable $e) {
            throw new RuntimeException('Error processing multi-chunk document ID: ' . $document->id, 0, $e);
        }
    }
    
    /**
     * Save analysis results to the database
     */
    private function saveAnalysisResults(Document $document, array $analysisData): bool
    {
        try {
            $prompt = $this->config->getPrompt(AiPromptType::STANDARD);
            if (!$prompt) {
                throw new RuntimeException('Missing standard prompt configuration');
            }
            
            $processingMetadata = [
                'processed_at' => now()->toIso8601String(),
                'chunk_size' => $this->textChunkManager->getChunkSize(),
                'model' => $this->config->model,
                'provider' => $this->config->provider ? $this->config->provider->name : null
            ];
            
            $content = $this->resultProcessor->encodeToJson($analysisData);
            
            $document->analysis()->create([
                'content' => $content,
                'payload' => $analysisData,
                'ai_service_configuration_id' => $this->config->id,
                'ai_prompt_id' => $prompt->id,
                'processing_metadata' => $processingMetadata
            ]);
            
            return true;
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to save analysis for document ID: ' . $document->id, 0, $e);
        }
    }
}
