<?php

namespace App\Observers;

use Illuminate\Support\Facades\Storage;
use App\Models\Document;

class DocumentObserver
{

    public function deleted(Document $document): void
    {
        Storage::disk('public')->delete($document->path);
    }
}
