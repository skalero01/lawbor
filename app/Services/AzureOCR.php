<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AzureOcr
{
    protected string $endpoint;
    protected string $key;

    public function __construct()
    {
        $this->endpoint = config('app.ocr_endpoint') . '/vision/v3.2/read/analyze';
        $this->key = config('app.ocr_key');
    }

    /**
     * Envía el archivo a Azure OCR.
     *
     * @param string $filePath Ruta local al archivo (por ejemplo: storage_path('app/public/file.pdf'))
     * @return string Operation-Location (para consultar resultados)
     */
    public function sendFile(string $filePath): string
    {
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->key,
            'Content-Type' => 'application/octet-stream',
        ])->post($this->endpoint, file_get_contents($filePath));

        if (!$response->successful()) {
            throw new \Exception('OCR request failed: ' . $response->body());
        }

        return $response->header('Operation-Location');
    }

    /**
     * Consulta los resultados del OCR.
     *
     * @param string $operationUrl La URL que te devolvió el método sendFile()
     * @return array|null Texto extraído o null si aún está procesando
     */
    public function getResult(string $operationUrl): ?array
    {
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->key,
        ])->get($operationUrl);

        if (!$response->successful()) {
            throw new \Exception('Failed to retrieve OCR results: ' . $response->body());
        }

        $data = $response->json();

        if (($data['status'] ?? '') !== 'succeeded') {
            return null; // Aún procesando o falló
        }

        // Devuelve los textos detectados como arreglo
        return $data['analyzeResult']['readResults'] ?? [];
    }

    /**
     * Extrae el texto plano de los resultados del OCR.
     *
     * @param array $readResults
     * @return string
     */
    public function extractText(array $readResults): string
    {
        $text = '';

        foreach ($readResults as $page) {
            foreach ($page['lines'] ?? [] as $line) {
                $text .= $line['text'] . PHP_EOL;
            }
        }

        return $text;
    }
}
