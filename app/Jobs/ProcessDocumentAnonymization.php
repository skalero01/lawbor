<?php

namespace App\Jobs;

use App\Models\Document;
use App\Enums\Status;
use App\Services\DocumentAnonymizerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessDocumentAnonymization implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;
    public $tries = 1;

    public function __construct(public Document $document)
    {
        $this->onQueue('anonymization');
    }

    public function handle(): void
    {
        if (
            $this->document->status_anonymization === Status::Processing ||
            $this->document->status_anonymization === Status::Completed
        ) {
            return;
        }

        $this->document->update(['status_anonymization' => Status::Processing, 'error' => null]);

        $anonymizerService = new DocumentAnonymizerService();
        $anonymizedText = $anonymizerService->handle($this->document);

        $this->document->text()->update(['anonymized_text' => $anonymizedText]);

        $this->document->update([
            'status_anonymization' => Status::Completed,
            'anonymization_completed_at' => now(),
            'status_analysis' => Status::Queued
        ]);

        ProcessDocumentAiAnalysis::dispatch($this->document);
    }

    public function failed(Throwable $exception): void
    {
        $this->document->update(['status_anonymization' => Status::Error, 'error' => $exception->getMessage()]);
    }
}
