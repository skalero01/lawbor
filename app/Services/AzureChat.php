<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\DocumentText;
use App\Models\DocumentAnalysis;

class AzureChat
{
    protected string $baseUrl, $model;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('AZURE_OPENAI_BASE_URL'); 
        $this->apiKey = env('AZURE_OPENAI_API_KEY'); 
    }

    public function sendMessage(DocumentText $DocumentText)
    {
        // Instrucciones iniciales del sistema
        $messages = [
            [
                'role' => 'system',
                'content' => DocumentAnalysis::getColumn('chat_prompt')."\nRegresa el mensaje en el idioma ".$DocumentText->idea->language
            ]
        ];

        $DocumentTexts = $DocumentText->idea->DocumentTexts()->where('id', '!=', $DocumentText->id)->oldest()->get();
        $messages = array_merge($messages, $DocumentTexts
            ->map(fn($meet) => [
                'role' => 'system',
                'content' => "Resume of DocumentText from {$meet->created_at}: ".$meet->resume
            ])
            ->toArray()
        );

        $messages = array_merge($messages, $DocumentText->messages()
            ->orderBy('created_at')
            ->whereNotIn('role', ['error'])
            ->get()
            ->map(fn($msg) => [
                'role' => $msg->role,
                'content' => $msg->message
            ])
            ->toArray()
        );

        $response = $this->callApi($messages);
        if (!$response->successful()) {
            $DocumentText->messages()->create([
                'message' => $response->json()['error']['message'] ?? 'Error desconocido',
                'role' => 'error'
            ]);
            return;
        }

        $response = $response->json('choices.0.message.content') ?? null;
        $DocumentText->messages()->create([
            'message' => $response,
            'role' => 'assistant'
        ]);
    }

    public function callApi($messages)
    {
        return Http::timeout(600)->withHeaders([
            'Content-Type' => 'application/json',
            'api-key' => $this->apiKey,
        ])->post($this->baseUrl, [
            'messages' => $messages,
            'model' => $this->model
        ]);
    }
}
