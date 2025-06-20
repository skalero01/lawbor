<?php

namespace App\Services\DocumentAnalysis;

use App\Models\AiServiceConfiguration;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

/**
 * Handles communication with LLM providers (OpenAI, Generic)
 */
class LlmClient
{
    private AiServiceConfiguration $config;

    public function __construct(AiServiceConfiguration $config)
    {
        $this->config = $config;
    }

    /**
     * Send a prompt to the configured LLM provider and get response
     */
    public function sendPromptToLlm(string $prompt): string
    {
        $provider = $this->config->provider;
        if (!$provider) {
            throw new RuntimeException('No provider found for analysis service');
        }
        
        $httpClient = $this->prepareHttpClient($provider->api_key);
        
        try {
            return $provider->name === 'OpenAI'
                ? $this->sendToOpenAi($provider->base_url, $httpClient, $prompt)
                : $this->sendToGenericProvider($provider->base_url, $httpClient, $prompt);
        } catch (Throwable $e) {
            throw new RuntimeException('Error sending prompt to LLM: ' . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Prepare HTTP client with proper configuration
     */
    private function prepareHttpClient(?string $apiKey): PendingRequest
    {
        $httpClient = Http::timeout($this->config->timeout_seconds ?? 30);
        if ($apiKey) {
            $httpClient = $httpClient->withToken($apiKey);
        }
        return $httpClient;
    }
    
    /**
     * Send prompt to OpenAI API
     */
    private function sendToOpenAi(string $baseUrl, PendingRequest $httpClient, string $prompt): string
    {
        $endpoint = "{$baseUrl}/chat/completions";
        $response = $httpClient->post($endpoint, [
            'model' => $this->config->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant specialized in analyzing legal documents.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => $this->config->temperature ?? 0.7,
            'max_tokens' => $this->config->max_tokens ?? 2000,
        ]);
        
        if (!$response->successful()) {
            throw new RuntimeException('OpenAI API error: ' . $response->body());
        }
        
        $content = $response->json('choices.0.message.content');
        if (empty($content)) {
            throw new RuntimeException('Empty content in OpenAI response');
        }
        
        return $content;
    }
    
    /**
     * Send prompt to generic LLM provider (e.g., Ollama)
     */
    private function sendToGenericProvider(string $baseUrl, PendingRequest $httpClient, string $prompt): string
    {
        $endpoint = "{$baseUrl}/generate";
        $response = $httpClient->post($endpoint, [
            'model' => $this->config->model,
            'prompt' => $prompt,
            'temperature' => $this->config->temperature ?? 0.7,
            'max_tokens' => $this->config->max_tokens ?? 2000,
        ]);
        
        if (!$response->successful()) {
            throw new RuntimeException('LLM API error: ' . $response->body());
        }
        
        $content = $response->json('response');
        if (empty($content)) {
            throw new RuntimeException('Empty content in LLM response');
        }
        
        return $content;
    }
}
