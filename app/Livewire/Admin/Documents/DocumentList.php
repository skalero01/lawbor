<?php

namespace App\Livewire\Admin\Documents;

use App\Models\Document;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Livewire\WithPagination;

class DocumentList extends Component
{
    use WireUiActions, WithPagination;

    /** @var \App\Models\User */
    public $user;

    #[On('documentUploaded')]
    public function refreshList()
    {
        $this->render();
    }

    public function mount()
    {
        $this->user = auth()->user();
    }

    #[On('deleteDocument')]
    public function deleteDocument(Document $document)
    {
        $document->delete();

        $this->notification()->success(
            title: 'Éxito',
            description: 'El documento ha sido eliminado correctamente.'
        );
    }

    public function deleteDocumentConfirm(Document $document): void
    {
        $this->dialog()->confirm([
            'title' => '¿Seguro de eliminar?',
            'description' => "¿Eliminar el documento '$document->name'? Esta acción no se puede deshacer.",
            'icon' => 'warning',
            'accept' => [
                'label' => 'Sí, eliminar',
                'method' => 'deleteDocument',
                'params' => $document,
            ],
            'reject' => [
                'label' => 'No, cancelar',
            ],
        ]);
    }

    public function render()
    {
        $documents = $this->user->documents()
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('livewire.admin.documents.document-list', compact('documents'));
    }
}
