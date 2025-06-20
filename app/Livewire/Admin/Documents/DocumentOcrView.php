<?php

namespace App\Livewire\Admin\Documents;

use App\Enums\Status;
use App\Jobs\ProcessDocumentAnonymization;
use App\Models\Document;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class DocumentOcrView extends Component
{
    use WireUiActions;

    public Document $document;
    public ?string $ocrText = null;

    public function mount(Document $document)
    {
        $this->document = $document;

        $this->loadOcrText();
    }

    private function loadOcrText(): void
    {
        $this->ocrText = $this->document->text->original_text ?? null;

        if (!$this->ocrText) {
            notify('Error', 'El documento no tiene texto OCR procesado.', 'error');
            $this->redirect(route('admin.documents'));
        }
    }

    public function processAnonymization(): void
    {
        if ($this->document->status_anonymization === Status::Processing) {
            $this->notification()->warning(
                title: 'Advertencia',
                description: 'El documento ya está en proceso de anonimización.'
            );
            return;
        }

        $this->document->update(['status_anonymization' => Status::Pending]);
        ProcessDocumentAnonymization::dispatch($this->document);

        $this->notification()->success(
            title: 'Procesamiento iniciado',
            description: 'El documento ha sido enviado para anonimización. Este proceso puede tardar unos minutos.'
        );

        $this->loadOcrText();
    }

    public function render()
    {
        $breadcrumb = [
            ['label' => __('Documentos'), 'url' => route('admin.documents')],
            ['label' => __('Texto Ocr')]
        ];
        return view('livewire.admin.documents.document-ocr-view')
            ->title(__('Texto Ocr: ') . $this->document->name)
            ->layoutData(compact('breadcrumb'));
    }
}
