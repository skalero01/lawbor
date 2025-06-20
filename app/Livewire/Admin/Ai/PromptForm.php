<?php

namespace App\Livewire\Admin\Ai;

use App\Enums\AiPromptType;
use App\Enums\AiServiceType;
use App\Models\AiPrompt;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class PromptForm extends Component
{
    use WireUiActions;

    public AiPrompt $prompt;
    public array $analysis_fields = [];
    public bool $isEditMode = false;

    protected $rules = [
        'prompt.name' => 'required|string|max:255',
        'prompt.service_type' => 'required',
        'prompt.prompt_type' => 'required',
        'prompt.content' => 'required|string',
        'prompt.is_active' => 'boolean',
        'prompt.is_default' => 'boolean',
        'prompt.description' => 'nullable|string',
        'analysis_fields' => 'nullable|array',
    ];

    public function mount(AiPrompt $prompt)
    {
        $this->prompt = $prompt;
        if ($prompt->exists) {
            $this->isEditMode = true;
            $this->loadAnalysisFields();
        }
    }

    protected function loadAnalysisFields()
    {
        if (empty($this->prompt->analysis_fields) || !is_array($this->prompt->analysis_fields)) {
            return;
        }

        $fields = [];
        foreach ($this->prompt->analysis_fields as $key => $description) {
            $fields[] = [
                'key' => $key,
                'description' => $description
            ];
        }
        $this->analysis_fields = $fields;
    }

    public function updatedPromptServiceType()
    {
        $this->prompt->prompt_type = null;
    }

    public function updatedPromptIsDefault($value)
    {
        $existingDefault = AiPrompt::where('service_type', $this->prompt->service_type)
            ->where('prompt_type', $this->prompt->prompt_type)
            ->where('is_default', true)
            ->when($this->isEditMode, function ($query) {
                return $query->where('id', '!=', $this->prompt->id);
            })
            ->count() > 0;

        if ($existingDefault) {
            $this->notification()->warning(
                'Prompt predeterminado existente',
                "Ya existe un prompt predeterminado para {$this->prompt->service_type}/{$this->prompt->prompt_type}. Si continúas, se cambiará el predeterminado."
            );
        }
    }

    public function addAnalysisField()
    {
        $this->analysis_fields[] = [
            'key' => '',
            'description' => ''
        ];
    }

    public function removeAnalysisField($index)
    {
        unset($this->analysis_fields[$index]);
        $this->analysis_fields = array_values($this->analysis_fields);
    }

    public function save()
    {
        $this->validate();

        $this->updateDefaultStatus();
        $this->processAnalysisFields();
        $this->savePrompt();
    }

    protected function updateDefaultStatus()
    {
        if (!$this->prompt->is_default) {
            return;
        }

        AiPrompt::where('service_type', $this->prompt->service_type)
            ->where('prompt_type', $this->prompt->prompt_type)
            ->where('is_default', true)
            ->when($this->isEditMode, fn($query) => $query->where('id', '!=', $this->prompt->id))
            ->update(['is_default' => false]);
    }

    protected function processAnalysisFields()
    {
        $filteredFields = [];
        foreach ($this->analysis_fields as $field) {
            if (empty($field['key']) || empty($field['description'])) {
                continue;
            }
            $filteredFields[$field['key']] = $field['description'];
        }

        $this->prompt->analysis_fields = $filteredFields;
    }

    protected function savePrompt()
    {
        $this->prompt->save();
        $actionType = $this->isEditMode ? 'actualizado' : 'creado';

        $this->notification()->success(
            "Prompt {$actionType}",
            "El prompt ha sido {$actionType} correctamente."
        );

        if (!$this->isEditMode) {
            $this->resetForm();
        }
    }

    protected function resetForm()
    {
        $this->prompt = new AiPrompt();
        $this->analysis_fields = [];
    }

    public function render()
    {
        $promptTypes = [];
        
        if ($this->prompt->service_type) {
            $promptTypes = AiPromptType::forServiceType($this->prompt->service_type->value);
        }
        
        $serviceTypes = AiServiceType::keyValue();
        
        return view('livewire.admin.ai.prompt-form', compact('serviceTypes', 'promptTypes'));
    }
}
