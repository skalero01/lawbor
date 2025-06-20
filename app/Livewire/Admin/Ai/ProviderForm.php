<?php

namespace App\Livewire\Admin\Ai;

use App\Models\AiProvider;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ProviderForm extends Component
{
    use WireUiActions;

    public AiProvider $provider;
    public bool $isEditMode = false;
    public string $apiKeyInput = '';

    protected const API_KEY_PLACEHOLDER = '••••••••••••••••';

    protected $rules = [
        'provider.name' => 'required|string|max:255',
        'provider.base_url' => 'required|string|max:255',
        'apiKeyInput' => 'nullable|string|max:255',
        'provider.is_active' => 'boolean',
        'provider.description' => 'nullable|string',
    ];

    public function mount(AiProvider $provider)
    {
        $this->provider = $provider;
        if ($provider->exists) {
            $this->isEditMode = true;
            $this->setApiKeyPlaceholder();
        }
    }

    protected function setApiKeyPlaceholder()
    {
        if (!empty($this->provider->api_key)) {
            $this->apiKeyInput = self::API_KEY_PLACEHOLDER;
        }
    }

    public function save()
    {
        $this->validate();

        $this->updateApiKey();
        $this->saveProvider();
    }

    protected function updateApiKey()
    {
        if (empty($this->apiKeyInput) || $this->apiKeyInput === self::API_KEY_PLACEHOLDER) {
            return;
        }

        $this->provider->api_key = $this->apiKeyInput;
    }

    protected function saveProvider()
    {
        $this->provider->save();
        $actionType = $this->isEditMode ? 'actualizado' : 'creado';

        $this->notification()->success(
            "Proveedor {$actionType}",
            "El proveedor ha sido {$actionType} correctamente."
        );

        if ($this->isEditMode) {
            $this->setApiKeyPlaceholder();
        } else {
            $this->resetForm();
        }
    }

    protected function resetForm()
    {
        $this->provider = new AiProvider();
        $this->apiKeyInput = '';
    }

    public function cancel()
    {
        return redirect()->route('admin.ai.index');
    }

    public function render()
    {
        return view('livewire.admin.ai.provider-form');
    }
}
