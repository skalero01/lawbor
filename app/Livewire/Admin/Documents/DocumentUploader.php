<?php

namespace App\Livewire\Admin\Documents;

use App\Models\Document;
use App\Jobs\ProcessDocumentOcr;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Spatie\LivewireFilepond\WithFilePond;
use WireUi\Traits\WireUiActions;

class DocumentUploader extends Component
{
    use WithFilePond, WithFileUploads, WireUiActions;

    #[Validate([
        'document' => 'required|file|mimes:pdf|max:204800', // 200MB
    ])]
    public $document;

    protected function messages(): array
    {
        return [
            'document.required' => __('Seleccione un documento para cargar.'),
            'document.mimes'    => __('El documento debe ser un archivo PDF.'),
            'document.max'      => __('El documento no puede exceder 200MB.'),
        ];
    }

    public function validateUploadedFile(): bool
    {
        $this->validate();
        $this->save();

        return true;
    }

    public function save(): void
    {
        $user = auth()->user();

        $originalName = $this->document->getClientOriginalName();
        $size = $this->document->getSize();
        $path = $this->document->store("documents/{$user->id}", 'public');

        if (!$path) {
            $this->notification()->error('Error', __('No se pudo guardar el documento. Inténtelo de nuevo.'));
            return;
        }

        $document = Document::create([
            'user_id' => $user->id,
            'name'    => $originalName,
            'path'    => $path,
            'size'    => $size,
            'error'   => null,
        ]);

        ProcessDocumentOcr::dispatch($document);

        $this->dispatch('documentUploaded');
        $this->notification()->success('Success', __('¡Documento cargado exitosamente y puesto en cola para procesamiento!'));
        $this->reset('document');
    }

    public function render()
    {
        return view('livewire.admin.documents.document-uploader');
    }
}
