<?php

namespace App\Livewire\Admin\Documents;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocumentRehydratedView extends Component
{
    public Document $document;
    public string $rehydratedText;

    public function mount(Document $document): void
    {
        $this->document = $document;

        if (
            !$this->document->text ||
            !$this->document->text->anonymized_text ||
            $this->document->aliases->isEmpty()
        ) {
            notify('Error', 'No hay datos suficientes para mostrar el texto rehidratado.', 'error');
            $this->redirect(route('admin.documents'));
        }

        $this->rehydratedText = $this->rehydrateText(
            $this->document->text->anonymized_text,
            $this->document->aliases
        );
    }

    protected function rehydrateText(string $text, $aliases): string
    {
        foreach ($aliases as $alias) {
            $text = str_replace(
                $alias->key,
                '<span class="bg-yellow-100 px-1 rounded">' . e($alias->value) . '</span>',
                $text
            );
        }

        return $text;
    }

    public function render()
    {
        $breadcrumb = [
            ['label' => __('Documentos'), 'url' => route('admin.documents')],
            ['label' => __('Texto Rehidratado')]
        ];
        return view('livewire.admin.documents.document-rehydrated-view')
            ->title(__('Texto Rehidratado: ') . $this->document->name)
            ->layoutData(compact('breadcrumb'));
    }
}
