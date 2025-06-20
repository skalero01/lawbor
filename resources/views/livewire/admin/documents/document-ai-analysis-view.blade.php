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

        <div class="mb-6">
            <div class="mb-4">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border dark:border-gray-600 overflow-auto max-h-screen">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300">Análisis de IA</h5>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">
                            Análisis completo
                        </span>
                    </div>
                    @if($document->analysis && !empty($document->analysis->payload))
                        <div class="space-y-4">
                            @foreach($document->analysis->payload as $key => $value)
                                <div class="border-t dark:border-gray-600 pt-3">
                                    <h6 class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">{{ ucfirst(str_replace('_', ' ', $key)) }}:</h6>
                                    <div class="bg-white dark:bg-gray-800 p-3 rounded-md text-sm text-gray-700 dark:text-gray-300 border dark:border-gray-600">
                                        @if(is_array($value))
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach($value as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {!! nl2br(e($value)) !!}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 pt-4 border-t dark:border-gray-600">
                            <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Datos JSON completos:</h5>
                            <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded-md text-xs overflow-auto max-h-96 text-gray-800 dark:text-gray-300 border dark:border-gray-600">{{ json_encode($document->analysis->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-8">
                            <x-icon name="chart-bar" class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" />
                            <p class="text-center text-gray-500 dark:text-gray-400">No hay datos de análisis disponibles.</p>
                            <p class="text-center text-gray-400 dark:text-gray-500 text-sm mt-2">El documento aún no ha sido analizado por IA.</p>
                        </div>
                    @endif
                </div>
            </div>            
        </div>

    </div>
</div>
