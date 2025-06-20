<div class="transition-all duration-200">
   
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Configuración de Servicios de IA</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Administra los proveedores, prompts y configuraciones de servicios de IA.</p>
    </div>

    <div class="mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <button wire:click="setActiveTab('configurations')" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 {{ $activeTab === 'configurations' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                    Configuraciones
                </button>
                <button wire:click="setActiveTab('providers')" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 {{ $activeTab === 'providers' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                    Proveedores
                </button>
                <button wire:click="setActiveTab('prompts')" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 {{ $activeTab === 'prompts' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                    Prompts
                </button>
            </nav>
        </div>
    </div>

    <div>
        @if ($activeTab === 'configurations')
            <div class="mb-4 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Configuraciones de Servicios</h2>
                <a href="{{ route('admin.ai.configurations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Nueva Configuración
                </a>
            </div>
            
            <!-- Configuraciones de Anonimización -->
            <div class="mb-6">
                <h3 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Anonimización</h3>
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md transition-colors duration-150">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @php $hasAnonymizationConfigs = false; @endphp
                        @foreach ($configurations as $config)
                            @if ($config->service_type->isAnonymization())
                                @php $hasAnonymizationConfigs = true; @endphp
                                <li>
                                    <a href="{{ route('admin.ai.configurations.edit', $config) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <div class="px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">{{ $config->name }}</p>
                                                    <div class="ml-2 flex-shrink-0 flex">
                                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                            {{ $config->is_active ? 'Activo' : 'Inactivo' }}
                                                        </p>
                                                        @if ($config->is_default)
                                                            <p class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                                Predeterminado
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                        Proveedor: {{ $config->provider->name }}
                                                    </p>
                                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                        Modelo: {{ $config->model }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        
                        @if (!$hasAnonymizationConfigs)
                            <li class="px-4 py-5 sm:px-6">
                                <div class="text-center text-sm text-gray-500">
                                    No hay configuraciones de anonimización disponibles.
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <!-- Configuraciones de Análisis -->
            <div>
                <h3 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Análisis</h3>
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md transition-colors duration-150">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @php $hasAnalysisConfigs = false; @endphp
                        @foreach ($configurations as $config)
                            @if ($config->service_type->isAnalysis())
                                @php $hasAnalysisConfigs = true; @endphp
                                <li>
                                    <a href="{{ route('admin.ai.configurations.edit', $config) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <div class="px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">{{ $config->name }}</p>
                                                    <div class="ml-2 flex-shrink-0 flex">
                                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                            {{ $config->is_active ? 'Activo' : 'Inactivo' }}
                                                        </p>
                                                        @if ($config->is_default)
                                                            <p class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                                Predeterminado
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                        Proveedor: {{ $config->provider->name }}
                                                    </p>
                                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                        Modelo: {{ $config->model }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        
                        @if (!$hasAnalysisConfigs)
                            <li class="px-4 py-5 sm:px-6">
                                <div class="text-center text-sm text-gray-500">
                                    No hay configuraciones de análisis disponibles.
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            
        @elseif ($activeTab === 'providers')
            <div class="mb-4 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Proveedores de IA</h2>
                <a href="{{ route('admin.ai.providers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Nuevo Proveedor
                </a>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md transition-colors duration-150">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($providers as $provider)
                        <li>
                            <a href="{{ route('admin.ai.providers.edit', $provider) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">{{ $provider->name }}</p>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $provider->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                    {{ $provider->is_active ? 'Activo' : 'Inactivo' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                URL Base: {{ $provider->base_url }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-4 py-5 sm:px-6">
                            <div class="text-center text-sm text-gray-500">
                                No hay proveedores disponibles.
                            </div>
                        </li>
                    @endforelse
                </ul>
                
                <!-- Se eliminó la paginación para mantener consistencia con las otras secciones -->
            </div>
            
        @elseif ($activeTab === 'prompts')
            <div class="mb-4 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Prompts de IA</h2>
                <a href="{{ route('admin.ai.prompts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Nuevo Prompt
                </a>
            </div>
            
            <!-- Prompts de Anonimización -->
            <div class="mb-6">
                <h3 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Anonimización</h3>
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md transition-colors duration-150">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @php $hasAnonymizationPrompts = false; @endphp
                        @foreach ($prompts as $prompt)
                            @if ($prompt->service_type->isAnonymization())
                                @php $hasAnonymizationPrompts = true; @endphp
                                <li>
                                    <a href="{{ route('admin.ai.prompts.edit', $prompt) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <div class="px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">{{ $prompt->name }}</p>
                                                    <div class="ml-2 flex-shrink-0 flex">
                                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $prompt->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                            {{ $prompt->is_active ? 'Activo' : 'Inactivo' }}
                                                        </p>
                                                        @if ($prompt->is_default)
                                                            <p class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                                Predeterminado
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                        Tipo: {{ $prompt->prompt_type }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        
                        @if (!$hasAnonymizationPrompts)
                            <li class="px-4 py-5 sm:px-6">
                                <div class="text-center text-sm text-gray-500">
                                    No hay prompts de anonimización disponibles.
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <!-- Prompts de Análisis -->
            <div>
                <h3 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Análisis</h3>
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md transition-colors duration-150">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @php $hasAnalysisPrompts = false; @endphp
                        @foreach ($prompts as $prompt)
                            @if ($prompt->service_type->isAnalysis())
                                @php $hasAnalysisPrompts = true; @endphp
                                <li>
                                    <a href="{{ route('admin.ai.prompts.edit', $prompt) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <div class="px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">{{ $prompt->name }}</p>
                                                    <div class="ml-2 flex-shrink-0 flex">
                                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $prompt->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                            {{ $prompt->is_active ? 'Activo' : 'Inactivo' }}
                                                        </p>
                                                        @if ($prompt->is_default)
                                                            <p class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                                Predeterminado
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                        Tipo: {{ $prompt->prompt_type }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        
                        @if (!$hasAnalysisPrompts)
                            <li class="px-4 py-5 sm:px-6">
                                <div class="text-center text-sm text-gray-500">
                                    No hay prompts de análisis disponibles.
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>
