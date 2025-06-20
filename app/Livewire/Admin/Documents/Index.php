<?php

namespace App\Livewire\Admin\Documents;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $breadcrumb = [
            ['label' => __('Documentos'), 'url' => route('admin.documents')],
        ];
        return view('livewire.admin.documents.index')
            ->title('Documentos')
            ->layoutData(compact('breadcrumb'));
    }
}
