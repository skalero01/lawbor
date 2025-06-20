<div class="py-6 px-4 sm:px-6 lg:px-8 transition-colors duration-200">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Texto OCR: {{ $documentName ?? 'Documento' }}
            </h1>
        </div>

        @if($ocrText)
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg transition-colors duration-150">
                <div class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Texto extraído mediante OCR</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Este es el texto completo extraído del documento.</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-600">
                    <div class="p-6 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-mono bg-gray-50 dark:bg-gray-700 overflow-auto max-h-screen">
                        {{ $ocrText }}
                    </div>
                </div>
            </div>

            <div class="mt-6 flex space-x-3">
                <button type="button" id="copyButton" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-150">
                    <x-icon name="clipboard-document" class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" />
                    Copiar al portapapeles
                </button>
                
                <button type="button" wire:click="processAnonymization" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-150">
                    <x-icon name="shield-check" class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" />
                    Procesar con Anonimización
                </button>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const copyButton = document.getElementById('copyButton');
                    const textContainer = document.querySelector('.whitespace-pre-wrap');
                    
                    copyButton.addEventListener('click', function() {
                        // create a temporary textarea element
                        const textarea = document.createElement('textarea');
                        textarea.value = textContainer.innerText;
                        textarea.setAttribute('readonly', '');
                        textarea.style.position = 'absolute';
                        textarea.style.left = '-9999px';
                        document.body.appendChild(textarea);
                        
                        // Select the text and copy
                        textarea.select();
                        document.execCommand('copy');
                        
                        // remove the temporary textarea element
                        document.body.removeChild(textarea);
                        
                        // Show success notification
                        $wireui.notify({
                            title: 'Texto copiado al portapapeles',
                            description: 'El texto ha sido copiado al portapapeles',
                            icon: 'success'
                        })
                    });
                });
            </script>
        @endif
    </div>
</div>
