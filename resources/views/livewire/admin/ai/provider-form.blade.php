<div class="transition-all duration-200">
    
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $isEditMode ? 'Editar Proveedor de IA' : 'Nuevo Proveedor de IA' }}
        </h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $isEditMode ? 'Actualiza la información del proveedor de IA.' : 'Configura un nuevo proveedor de IA para los servicios de análisis o anonimización.' }}
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg transition-colors duration-150">
        <div class="px-4 py-5 sm:p-6">
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input label="Nombre" placeholder="Ej: OpenAI" wire:model="provider.name" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input label="URL Base" placeholder="Ej: https://api.openai.com/v1" wire:model="provider.base_url" />
                    </div>

                    <div class="sm:col-span-6">
                        <x-input label="API Key" placeholder="Clave de API (se almacenará cifrada)" wire:model="apiKeyInput" type="text" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            La clave de API se almacenará de forma segura y cifrada. Si estás editando y no deseas cambiar la clave, deja este campo como está.
                        </p>
                    </div>

                    <div class="sm:col-span-6">
                        <x-textarea label="Descripción" placeholder="Descripción del proveedor" wire:model="provider.description" rows="3" />
                    </div>

                    <div class="sm:col-span-6">
                        <x-toggle label="Activo" wire:model="provider.is_active" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Los proveedores inactivos no estarán disponibles para nuevas configuraciones.
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <x-button type="button" flat label="Cancelar" wire:click="cancel" />
                    <x-button type="submit" primary label="{{ $isEditMode ? 'Actualizar' : 'Crear' }}" spinner="save" />
                </div>
            </form>
        </div>
    </div>
</div>
