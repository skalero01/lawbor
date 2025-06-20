<div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mt-6 transition-colors duration-200" wire:poll.15s>
        <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">Mis Documentos</h3>

        @if ($documents->count() > 0)
            <div class="overflow-x-auto relative">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tamaño
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Fecha de Subida
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Procesamiento
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($documents as $document)
                            <tr wire:key="{{ $document->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <x-icon name="document-text" class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-2" />
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $document->name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{-- Format size --}}
                                    {{ number_format($document->size / 1024, 2) }} KB
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $document->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4  text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col space-y-1">
                                        <div class="flex items-center ">
                                            <span class="{{ $document->status_ocr->classBadge() }} me-2 ">{{ $document->status_ocr->label() }}</span>
                                            <span>OCR</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="{{ $document->status_anonymization->classBadge() }} me-2 ">{{ $document->status_anonymization->label() }}</span>
                                            <span>Anonimización</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="{{ $document->status_analysis->classBadge() }} me-2 ">{{ $document->status_analysis->label() }}</span>
                                            <span>Análisis IA</span>
                                        </div>
                                    </div>
                                    @if ($document->error)
                                        <div class="mt-3 hs-tooltip ti-main-tooltip !max-w-[276px] [--trigger:click] [--placement:top]">
                                            <a class="hs-tooltip-toggle ti-main-tooltip-toggle" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="fill-danger" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                                            <div class="hs-tooltip-content ti-main-tooltip-content border border-defaultborder dark:border-defaultborder/10 !bg-danger !text-white !py-4 !px-4" role="tooltip">
                                                <p>{{ $document->error }}</p>
                                            </div>
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative">
                                    <div class="flex justify-end">
                                        <x-dropdown width="56" position="left">
                                            <x-slot name="trigger">
                                                <button class="text-gray-500 dark:text-gray-400 hover:text-gray-700 focus:outline-none">
                                                    <x-icon name="ellipsis-vertical" class="w-5 h-5" />
                                                </button>
                                            </x-slot>
                                            
                                            <x-dropdown.item icon="arrow-down-tray" href="{{ Storage::url($document->path) }}" download="{{ $document->name }}">
                                                Descargar original
                                            </x-dropdown.item>
                                            
                                            <x-dropdown.item 
                                                icon="document-text" 
                                                href="{{ route('admin.documents.ocr', ['document' => $document]) }}"
                                            >
                                                Ver texto OCR
                                            </x-dropdown.item>
                                            
                                            <x-dropdown.item 
                                                icon="eye-slash" 
                                                href="{{ route('admin.documents.anonymized', ['document' => $document]) }}"
                                            >
                                                Ver datos anonimizados
                                            </x-dropdown.item>
                                            
                                            <x-dropdown.item 
                                                icon="chart-bar" 
                                                href="{{ route('admin.documents.ai-analysis', ['document' => $document]) }}"
                                            >
                                                Ver análisis IA
                                            </x-dropdown.item>
                                            
                                            <x-dropdown.item 
                                                icon="eye" 
                                                href="{{ route('admin.documents.rehydrated', ['document' => $document]) }}"
                                            >
                                                Ver datos rehidratados
                                            </x-dropdown.item>
                                            
                                            <x-dropdown.item 
                                                separator
                                                icon="trash" 
                                                wire:click="deleteDocumentConfirm({{ $document->id }})"
                                                class="!text-red-600 hover:!text-red-700 focus:outline-none"
                                            >
                                                Eliminar
                                            </x-dropdown.item>
                                        </x-dropdown>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $documents->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-8">
                <x-icon name="document-text" class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" />
                <p class="text-center text-gray-500 dark:text-gray-400">No se han subido documentos aún.</p>
                <p class="text-center text-gray-400 dark:text-gray-500 text-sm mt-2">Los documentos que subas aparecerán aquí.</p>
            </div>
        @endif
    </div>
</div>
