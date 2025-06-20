<?php

namespace App\Livewire\Admin\Documents;

use App\Jobs\ProcessDocumentAiAnalysis;
use App\Models\Document;
use App\Enums\Status;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class DocumentAnonymizedView extends Component
{
    use WireUiActions;

    public Document $document;
    public $aliases;

    public function mount(Document $document): void
    {
        $this->document = $document;

        if (!$this->hasAnonymizedText()) {
            notify('Error', 'El documento no tiene texto anonimizado.', 'error');
            $this->redirect(route('admin.documents'));
        }

        $this->aliases = $this->document->aliases;
    }

    private function hasAnonymizedText(): bool
    {
        return !empty($this->document->text?->anonymized_text);
    }

    public function runAiAnalysis(): void
    {
        if ($this->document->status_analysis === Status::Processing) {
            $this->notification()->warning(
                title: 'Advertencia',
                description: 'El análisis de IA ya está en proceso para este documento.'
            );
            return;
        }

        $this->document->update(['status_analysis' => Status::Pending]);

        ProcessDocumentAiAnalysis::dispatch($this->document);

        $this->notification()->success(
            title: 'Procesamiento iniciado',
            description: 'El documento ha sido enviado para análisis de IA. Este proceso puede tardar unos minutos.'
        );
    }

    public function render()
    {
        $breadcrumb = [
            ['label' => __('Documentos'), 'url' => route('admin.documents')],
            ['label' => __('Anonimizado')]
        ];

        return view('livewire.admin.documents.document-anonymized-view')
            ->title(__('Anonimizado: ') . $this->document->name)
            ->layoutData(compact('breadcrumb'));
    }
}
