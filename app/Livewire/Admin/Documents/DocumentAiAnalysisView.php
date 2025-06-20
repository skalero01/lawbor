<?php

namespace App\Livewire\Admin\Documents;

use App\Models\Document;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class DocumentAiAnalysisView extends Component
{
    use WireUiActions;

    public Document $document;

    public function mount(Document $document)
    {
        $this->document = $document;

        if (!$this->document->analysis) {
            notify('Error', 'El documento no tiene análisis asociado.', 'error');
            $this->redirect(route('admin.documents'));
        }
    }

    public function render()
    {
        $breadcrumb = [
            ['label' => __('Documentos'), 'url' => route('admin.documents')],
            ['label' => __('Análisis del documento')]
        ];
        return view('livewire.admin.documents.document-ai-analysis-view')
            ->title(__('Análisis del documento: ') . $this->document->name)
            ->layoutData(compact('breadcrumb'));
    }
}
