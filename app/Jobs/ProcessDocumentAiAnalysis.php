<?php

namespace App\Jobs;

use App\Models\Document;
use App\Services\DocumentAnalysis\DocumentAnalysisService;
use App\Enums\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Throwable;

class ProcessDocumentAiAnalysis implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 3600;

    public function __construct(public Document $document)
    {
        $this->onQueue('ai-analysis');
    }

    public function handle(DocumentAnalysisService $documentAnalysisService): void
    {
        if ($this->document->status_analysis === Status::Processing || 
            $this->document->status_analysis === Status::Completed) {
            return;
        }

        $this->document->update(['status_analysis' => Status::Processing, 'error' => null]);

        $anonymizedText = $this->document->text->anonymized_text ?? '';

        $documentAnalysisService->analyzeDocument($this->document, $anonymizedText);

        $this->document->update([
            'status_analysis' => Status::Completed,
            'analysis_completed_at' => now()
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $this->document->update(['status_analysis' => Status::Error, 'error' => $exception->getMessage()]);
    }
}
