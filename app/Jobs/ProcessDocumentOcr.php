<?php

namespace App\Jobs;

use App\Models\Document;
use App\Enums\Status;
use App\Services\AzureOCR;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class ProcessDocumentOcr implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1800; // 30 minutes
    public $tries = 1;

    public function __construct(public Document $document) 
    {
        $this->onQueue('ocr');
    }

    public function handle(): void
    {
        if ($this->document->status_ocr === Status::Processing || $this->document->status_ocr === Status::Completed) {
            return;
        }

        $this->document->update(['status_ocr' => Status::Processing, 'error' => null]);

        $inputPath = $this->document->path;
        $outputPathTxt = "{$inputPath}.ocr.txt";
        $outputPathPdf = "{$inputPath}.ocr.pdf";

        $this->spawnOcrProcess(
            // Pass the paths as absolute to the OCR process
            $this->disk()->path($inputPath),
            $this->disk()->path($outputPathTxt),
            $this->disk()->path($outputPathPdf)
        );

        throw_unless(
            $this->disk()->exists($outputPathTxt),
            'OCR file not found.'
        );

        $rawText = $this->disk()->get($outputPathTxt);

        $this->disk()->delete($outputPathTxt);
        $this->disk()->delete($outputPathPdf);

        $this->document->text()->create(['original_text' => $rawText]);

        $this->document->update([
            'status_ocr' => Status::Completed,
            'ocr_completed_at' => now(),
            'status_anonymization' => Status::Queued
        ]);

        ProcessDocumentAnonymization::dispatch($this->document);
    }

    public function failed(\Throwable $exception): void
    {
        $this->document->update(['status_ocr' => Status::Error, 'error' => $exception->getMessage()]);
    }

    private function disk()
    {
        return Storage::disk('public');
    }

    private function spawnOcrProcess(
        string $inputPath,
        string $outputPathTxt,
        string $outputPathPdf,
    ) {
        $ocr = new AzureOCR;
        $url = $ocr->sendFile($inputPath);
        $result = $ocr->getResult($url);
        return $ocr->extractText($result);
    }
}
