<?php

namespace App\Livewire\Admin\Ai;

use App\Enums\AiPromptType;
use App\Enums\AiServiceType;
use App\Models\AiPrompt;
use App\Models\AiProvider;
use App\Models\AiServiceConfiguration;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ConfigurationForm extends Component
{
    use WireUiActions;

    public AiServiceConfiguration $configuration;
    public bool $isEditMode = false;
    public array $selectedPrompts = [];
    public array $availablePrompts = [];

    protected $rules = [
        'configuration.name' => 'required|string|max:255',
        'configuration.service_type' => 'required',
        'configuration.provider_id' => 'required|exists:ai_providers,id',
        'configuration.model' => 'required|string|max:100',
        'configuration.timeout_seconds' => 'required|integer|min:1|max:1800',
        'configuration.max_chars_per_batch' => 'required|integer|min:1000|max:100000',
        'configuration.temperature' => 'required|numeric|min:0|max:2',
        'configuration.max_tokens' => 'required|integer|min:1|max:16000',
        'configuration.is_active' => 'boolean',
        'configuration.is_default' => 'boolean',
        'configuration.description' => 'nullable|string',
    ];

    public function mount(AiServiceConfiguration $configuration)
    {
        $this->configuration = $configuration;

        if ($configuration->exists) {
            $this->isEditMode = true;
            $this->loadSelectedPrompts();
        }

        $this->loadAvailablePrompts();
    }

    public function updatedConfigurationServiceType()
    {
        $this->loadAvailablePrompts();
        $this->selectedPrompts = [];
    }

    protected function loadAvailablePrompts()
    {
        if (!$this->configuration->service_type) {
            $this->availablePrompts = [];
            return;
        }

        $this->availablePrompts = AiPrompt::forServiceType($this->configuration->service_type)
            ->active()
            ->orderBy('prompt_type')
            ->orderBy('name')
            ->get()
            ->groupBy('prompt_type')
            ->toArray();
    }

    protected function loadSelectedPrompts()
    {
        if (!$this->configuration->service_parameters) {
            return;
        }

        $this->selectedPrompts = [];
        $params = $this->configuration->service_parameters;

        if (isset($params['prompt_id'])) {
            $this->selectedPrompts[AiPromptType::STANDARD->value] = $params['prompt_id'];
        }

        foreach (AiPromptType::values() as $type) {
            $paramKey = "{$type}_prompt_id";
            if (isset($params[$paramKey])) {
                $this->selectedPrompts[$type] = $params[$paramKey];
            }
        }
    }

    public function updatedConfigurationIsDefault($value)
    {
        $existingDefault = AiServiceConfiguration::where('service_type', $this->configuration->service_type)
            ->where('is_default', true)
            ->when($this->isEditMode, function ($query) {
                return $query->where('id', '!=', $this->configuration->id);
            })->count() > 0;

        if ($existingDefault) {
            $this->notification()->warning(
                'Configuración predeterminada existente',
                "Ya existe una configuración predeterminada para {$this->configuration->service_type}. Si continúas, se cambiará la predeterminada."
            );
        }
    }

    public function save()
    {
        $this->validate();

        $this->updateDefaultStatus();
        $this->updateServiceParameters();
        $this->saveConfiguration();
    }

    protected function updateDefaultStatus()
    {
        if (!$this->configuration->is_default) {
            return;
        }

        AiServiceConfiguration::where('service_type', $this->configuration->service_type)
            ->where('is_default', true)
            ->when($this->isEditMode, fn($query) => $query->where('id', '!=', $this->configuration->id))
            ->update(['is_default' => false]);
    }

    protected function updateServiceParameters()
    {
        $serviceParameters = $this->configuration->service_parameters ?? [];
        
        $promptTypes = array_merge(AiPromptType::values(), ['prompt_id']);
        foreach ($promptTypes as $type) {
            $key = $type === 'prompt_id' ? $type : "{$type}_prompt_id";
            unset($serviceParameters[$key]);
        }

        foreach ($this->selectedPrompts as $type => $promptId) {
            if (!$promptId) {
                continue;
            }

            $serviceParameters["{$type}_prompt_id"] = $promptId;

            $promptType = is_string($type) ? AiPromptType::tryFrom($type) : null;
            if ($promptType && $promptType->isStandard()) {
                $serviceParameters['prompt_id'] = $promptId;
            }
        }

        $this->configuration->service_parameters = $serviceParameters;
    }

    protected function saveConfiguration()
    {
        $this->configuration->save();
        $actionType = $this->isEditMode ? 'actualizada' : 'creada';

        $this->notification()->success(
            "Configuración {$actionType}",
            "La configuración ha sido {$actionType} correctamente."
        );

        if (!$this->isEditMode) {
            $this->configuration = new AiServiceConfiguration();
            $this->selectedPrompts = [];
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.ai.index');
    }

    public function render()
    {
        $providers = AiProvider::where('is_active', true)->orderBy('name')->get();
        $serviceTypes = AiServiceType::keyValue();
        
        return view('livewire.admin.ai.configuration-form', compact('providers', 'serviceTypes'));
    }
}
