<div class="transition-all duration-200">
    
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $isEditMode ? 'Editar Prompt de IA' : 'Nuevo Prompt de IA' }}
        </h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $isEditMode ? 'Actualiza la información del prompt de IA.' : 'Configura un nuevo prompt de IA para los servicios de análisis o anonimización.' }}
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg transition-colors duration-150">
        <div class="px-4 py-5 sm:p-6">
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input label="Nombre" placeholder="Ej: Prompt de Anonimización Estándar" wire:model="prompt.name" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-native-select 
                            label="Tipo de Servicio" 
                            wire:model.live="prompt.service_type"
                            placeholder="Selecciona el tipo de servicio"
                            :options="$serviceTypes"
                            option-key-value
                        />
                    </div>
                    <div class="sm:col-span-3">
                        <x-native-select 
                            label="Tipo de Prompt" 
                            wire:model.live="prompt.prompt_type"
                            placeholder="Selecciona el tipo de prompt"
                            :disabled="!$prompt->service_type"
                            :options="$promptTypes"
                            option-key-value
                        />
                    </div>

                    <div class="sm:col-span-6">
                        <x-textarea 
                            label="Contenido del Prompt" 
                            placeholder="Ingresa el contenido del prompt. Puedes usar @{{TEXT}} como placeholder para el texto a procesar y @{{FIELDS}} para los campos de análisis." 
                            wire:model="prompt.content" 
                            rows="10" 
                        />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Placeholders disponibles:
                            
                            @if($prompt->service_type && $prompt->service_type->isAnalysis())
                                
                                @if($prompt->prompt_type && $prompt->prompt_type->isChunk())
                                    <br>- <code>@{{TEXT}}</code>: Texto a procesar
                                    <br>- <code>@{{FIELDS}}</code>: Campos de análisis
                                    <br>- <code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">@{{CHUNK_NUMBER}}</code>: Número del fragmento actual
                                    <br>- <code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">@{{TOTAL_CHUNKS}}</code>: Número total de fragmentos
                                @elseif($prompt->prompt_type && $prompt->prompt_type->isCombination())
                                    <br>- <code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">@{{FIELD}}</code>: Nombre del campo a combinar
                                    <br>- <code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">@{{DESCRIPTION}}</code>: Descripción del campo
                                    <br>- <code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">@{{FIELD_VALUES}}</code>: Valores parciales del campo
                                @else
                                    <br>- <code>@{{TEXT}}</code>: Texto a procesar
                                    <br>- <code>@{{FIELDS}}</code>: Campos de análisis
                                @endif
                            @else
                                <br>- <code>@{{TEXT}}</code>: Texto a procesar
                            @endif
                        </p>
                    </div>

                    @if($prompt->service_type && $prompt->service_type->isAnalysis())
                        <div class="sm:col-span-6">
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Campos de Análisis</label>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Define los campos que se extraerán durante el análisis del documento.
                                </p>
                            </div>
                            
                            <div class="space-y-3">
                                @foreach($analysis_fields as $index => $field)
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-1">
                                            <x-input placeholder="Clave (ej: defendant_name)" wire:model="analysis_fields.{{ $index }}.key" />
                                        </div>
                                        <div class="flex-1">
                                            <x-input placeholder="Descripción (ej: Nombre del acusado)" wire:model="analysis_fields.{{ $index }}.description" />
                                        </div>
                                        <div>
                                            <x-button 
                                                icon="trash" 
                                                negative 
                                                wire:click="removeAnalysisField({{ $index }})" 
                                                type="button"
                                                class="mt-1"
                                            />
                                        </div>
                                    </div>
                                @endforeach
                                
                                <div>
                                    <x-button 
                                        icon="plus" 
                                        primary 
                                        wire:click="addAnalysisField" 
                                        type="button" 
                                        label="Agregar Campo"
                                    />
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="sm:col-span-6">
                        <x-textarea label="Descripción" placeholder="Descripción del prompt" wire:model="prompt.description" rows="3" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-toggle label="Activo" wire:model="prompt.is_active" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Los prompts inactivos no estarán disponibles para nuevas configuraciones.
                        </p>
                    </div>

                    <div class="sm:col-span-3">
                        <x-toggle label="Predeterminado" wire:model.live="prompt.is_default" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            El prompt predeterminado se utilizará cuando no se especifique otro.
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <x-button type="button" flat label="Cancelar" href="{{ route('admin.ai.index') }}" />
                    <x-button type="submit" primary label="{{ $isEditMode ? 'Actualizar' : 'Crear' }}" spinner="save" />
                </div>
            </form>
        </div>
    </div>
</div>
