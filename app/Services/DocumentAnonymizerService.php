<?php

namespace App\Services;

use App\Enums\AiPromptType;
use App\Enums\AiServiceType;
use App\Models\Document;
use App\Models\DocumentAlias;
use App\Models\AiServiceConfiguration;
use App\Models\AiPrompt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DocumentAnonymizerService
{
    protected int $chunkSize = 2000;
    protected AiServiceConfiguration $config;
    protected AiPrompt $prompt;

    public function __construct()
    {
        $this->config = AiServiceConfiguration::default(AiServiceType::ANONYMIZATION)->first();
        
        if (!$this->config) {
            throw new \RuntimeException('No active configuration found for anonymization service');
        }
        
        if (!$this->config->provider) {
            throw new \RuntimeException('No provider found for anonymization service');
        }
        
        $this->prompt = $this->config->getPrompt(AiPromptType::STANDARD);
        
        if (!$this->prompt) {
            throw new \RuntimeException('No prompt found for anonymization service');
        }
        
        if ($this->config->max_chars_per_batch) {
            $this->chunkSize = $this->config->max_chars_per_batch;
        }
    }
    
    public function handle(Document $document, ?int $chunkSize = null): string
    {
        try {
            $effectiveChunkSize = $chunkSize ?? $this->chunkSize;
    
            $text = $document->text->original_text;
    
            $chunks = $this->splitIntoChunks($text, $effectiveChunkSize);
    
            $fullOutput = $this->processChunks($document, $chunks);
    
            $fullOutput = $this->replaceEntities($document, $fullOutput);
    
            return $this->cleanMarkdownFormatting($fullOutput);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function processChunks(Document $document, array $chunks): string
    {
        $fullOutput = '';
        $providerName = $this->config->provider->name;

        foreach ($chunks as $chunk) {
            $prompt = $this->getPrompt($chunk);
            $response = $this->sendToOpenAi($prompt);

            if (!$response->successful()) {
                throw new \Exception('Error in anonymization: ' . $response->json('error.message'));
            }
            
            $content = $providerName === 'OpenAI' 
                ? ($response->json('choices.0.message.content') ?? '') 
                : ($response->json('response') ?? '');
            if (!$content) {
                throw new \RuntimeException('El contenido de la respuesta está vacío');
            }
            $fullOutput .= $content;
        }

        return $fullOutput;
    }

    private function sendToOpenAi(string $prompt)
    {
        $provider = $this->config->provider;
        
        $baseUrl = $provider->base_url;
        $apiKey = $provider->api_key;
        $model = $this->config->model;
        $timeoutSeconds = $this->config->timeout_seconds ?: 600;
        
        $endpoint = $provider->name === 'OpenAI' 
            ? "{$baseUrl}/chat/completions" 
            : "{$baseUrl}/generate";
        
        $httpClient = Http::timeout($timeoutSeconds);
        
        if ($apiKey) {
            $httpClient = $httpClient->withToken($apiKey);
        }
        
        if ($provider->name === 'OpenAI') {
            return $httpClient->post($endpoint, [
                'model' => $model,
                'temperature' => 0.0,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a specialized assistant for anonymizing sensitive information in legal documents.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);
        } else {
            return $httpClient->post($endpoint, [
                'model' => $model,
                'prompt' => $prompt,
                'temperature' => 0.0,
            ]);
        }
    }

    private function replaceEntities(Document $document, string $text): string
    {
        $document->aliases()->forceDelete();

        preg_match_all('/\[\[(.*?):(.*?)\]\]/s', $text, $matches);

        foreach ($matches[0] as $index => $match) {
            $entityType = $matches[1][$index];
            $originalValue = trim(preg_replace('/\s+/', ' ', $matches[2][$index]));
            $key = '[[ALIAS_' . Str::random(8) . ']]';

            DocumentAlias::create([
                'document_id' => $document->id,
                'key' => $key,
                'value' => $originalValue,
                'entity_type' => $entityType,
            ]);

            $text = str_replace($match, $key, $text);
        }

        return $text;
    }

    private function splitIntoChunks(string $text, int $maxLength): array
    {
        $sentences = preg_split('/(?<=[.?!])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $chunks = [];
        $current = '';

        foreach ($sentences as $sentence) {
            if (strlen($current . ' ' . $sentence) > $maxLength) {
                $chunks[] = trim($current);
                $current = $sentence;
            } else {
                $current .= ' ' . $sentence;
            }
        }

        if (trim($current)) {
            $chunks[] = trim($current);
        }

        return $chunks;
    }

    protected function cleanMarkdownFormatting(string $text): string
    {
        return preg_replace('/```[a-z]*\s*/', '', $text);
    }

    protected function getPrompt(string $chunk): string
    {
        $promptInstructions = $this->prompt->content;
        
        return strpos($promptInstructions, '{{TEXT}}') !== false
            ? str_replace('{{TEXT}}', $chunk, $promptInstructions)
            : $promptInstructions . "\n\nTexto a procesar:\n\"\"\"\n{$chunk}\n\"\"\"";
    }
}
