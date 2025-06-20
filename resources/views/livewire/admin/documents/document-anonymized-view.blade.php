<div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors duration-200">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                <button 
                    wire:click="runAiAnalysis"
                    wire:loading.attr="disabled"
                    class="flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-800 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    <x-icon name="bolt" class="w-5 h-5 mr-2" />
                    <span>Ejecutar Análisis IA</span>
                    <span wire:loading wire:target="runAiAnalysis" class="ml-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>

        <div class="mb-4">
            <h3 class="text-lg font-medium mb-2 text-gray-900 dark:text-white">{{ $document->name }}</h3>
            <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center flex-wrap">
                <span class="flex items-center">
                    <x-icon name="calendar" class="w-4 h-4 mr-1" />
                    {{ $document->created_at->format('d/m/Y H:i') }}
                </span>
                <span class="mx-2">•</span>
                <span class="flex items-center">
                    <x-icon name="document" class="w-4 h-4 mr-1" />
                    {{ number_format($document->size / 1024, 2) }} KB
                </span>
            </div>
        </div>

        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300">Entidades identificadas:</h4>
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                    {{ count($aliases) }} entidades
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @forelse ($aliases as $alias)
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md border dark:border-gray-600 transition-colors duration-150 hover:shadow-sm">
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-800 dark:text-gray-200 mb-1">{{ $alias->entity_type }}</span>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400 truncate">{{ $alias->value }}</span>
                                <x-icon name="arrow-right" class="w-4 h-4 mx-1 text-gray-400 dark:text-gray-500" />
                                <span class="text-indigo-600 dark:text-indigo-400 font-medium">{{ $alias->key }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 flex flex-col items-center justify-center py-8">
                        <x-icon name="eye-slash" class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" />
                        <p class="text-center text-gray-500 dark:text-gray-400">No se encontraron entidades para anonimizar.</p>
                        <p class="text-center text-gray-400 dark:text-gray-500 text-sm mt-2">El documento no contiene información personal identificable.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <div>
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300">Texto anonimizado:</h4>
                <button
                    onclick="copyToClipboard()"
                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-700 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/50 rounded hover:bg-indigo-200 dark:hover:bg-indigo-900 transition-colors duration-150"
                >
                    <x-icon name="clipboard-document" class="w-4 h-4 mr-1" />
                    Copiar texto
                </button>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md border dark:border-gray-600 overflow-auto max-h-screen">
                <pre id="anonymizedText" class="whitespace-pre-wrap text-sm text-gray-800 dark:text-gray-300 dark:bg-gray-700">{{ $this->document->text->anonymized_text }}</pre>
            </div>
        </div>
        
        <script>
        function copyToClipboard() {
            const text = document.getElementById('anonymizedText').innerText;
            navigator.clipboard.writeText(text).then(() => {
                console.log('Texto copiado al portapapeles');
            });
        }
        </script>

    </div>
</div>
