<div class="transition-all duration-200">
    
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $isEditMode ? 'Editar Configuración de Servicio de IA' : 'Nueva Configuración de Servicio de IA' }}
        </h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $isEditMode ? 'Actualiza la configuración del servicio de IA.' : 'Configura un nuevo servicio de IA para análisis o anonimización.' }}
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg transition-colors duration-150">
        <div class="px-4 py-5 sm:p-6">
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input label="Nombre" placeholder="Ej: Configuración de Análisis OpenAI" wire:model="configuration.name" />
                    </div>
                    <div class="sm:col-span-3">
                        <x-native-select 
                            label="Tipo de Servicio" 
                            wire:model.live="configuration.service_type"
                            placeholder="Selecciona el tipo de servicio"
                            :options="$serviceTypes"
                            option-key-value
                        />
                    </div>

                    <div class="sm:col-span-3">
                        <x-native-select 
                            label="Proveedor" 
                            wire:model="configuration.provider_id"
                            placeholder="Selecciona el proveedor"
                            :options="$providers"
                            option-label="name"
                            option-value="id"
                        />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input label="Modelo" placeholder="Ej: gpt-4-turbo" wire:model="configuration.model" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-number 
                            label="Tiempo de espera (segundos)" 
                            wire:model="configuration.timeout_seconds"
                            min="1"
                            max="3600"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <x-number 
                            label="Caracteres por lote" 
                            wire:model="configuration.max_chars_per_batch"
                            min="1000"
                            max="100000"
                            step="1000"
                        />
                    </div>

                    <div class="sm:col-span-1">
                        <x-number 
                            label="Temperatura" 
                            wire:model="configuration.temperature"
                            min="0"
                            max="2"
                            step="0.1"
                        />
                    </div>

                    <div class="sm:col-span-1">
                        <x-number 
                            label="Tokens máximos" 
                            wire:model="configuration.max_tokens"
                            min="100"
                            max="16000"
                            step="100"
                        />
                    </div>

                    @if($configuration->service_type && count($availablePrompts) > 0)
                        <div class="sm:col-span-6">
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prompts</label>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Selecciona los prompts a utilizar para cada tipo de operación.
                                </p>
                            </div>
                            <div class="space-y-4 mt-3">
                                @foreach($availablePrompts as $promptType => $prompts)
                                    <div class="border dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-700">
                                        
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Prompt de tipo: {{ ucfirst($promptType) }}
                                        </label>
                                        
                                        <x-native-select 
                                            wire:model="selectedPrompts.{{ $promptType }}"
                                            placeholder="Selecciona un prompt"
                                            :options="$prompts"
                                            option-label="name"
                                            option-value="id"
                                        />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="sm:col-span-6">
                        <x-textarea label="Descripción" placeholder="Descripción de la configuración" wire:model="configuration.description" rows="3" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-toggle label="Activo" wire:model="configuration.is_active" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Las configuraciones inactivas no estarán disponibles para los servicios.
                        </p>
                    </div>

                    <div class="sm:col-span-3">
                        <x-toggle label="Predeterminado" wire:model.live="configuration.is_default" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            La configuración predeterminada se utilizará cuando no se especifique otra.
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
