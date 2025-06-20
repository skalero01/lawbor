<div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors duration-200">
        <div class="mb-4 flex justify-between items-start">
            <div>
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
        </div>

        <div>
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300">Texto con alias aplicados:</h4>
                <button
                    onclick="copyToClipboard()"
                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-700 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/50 rounded hover:bg-indigo-200 dark:hover:bg-indigo-900 transition-colors duration-150"
                >
                    <x-icon name="clipboard-document" class="w-4 h-4 mr-1" />
                    Copiar texto
                </button>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md border dark:border-gray-600 overflow-auto max-h-screen" >
                <div id="rehydratedText" class="whitespace-pre-wrap text-sm text-gray-800 dark:text-gray-300">{!! $rehydratedText !!}</div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Nota: Los alias se muestran resaltados en amarillo.</p>
            
            <script>
            function copyToClipboard() {
                const text = document.getElementById('rehydratedText').innerText;
                navigator.clipboard.writeText(text).then(() => {
                    console.log('Texto copiado al portapapeles');
                });
            }
            </script>
        </div>
    </div>
</div>
