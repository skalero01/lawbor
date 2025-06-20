<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors duration-200">
    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">Subir nuevo documento</h3>
    {{-- FilePond Component --}}
    <div wire:ignore class="mb-4">
        <x-filepond::upload 
        wire:model="document" 
        multiple 
        required 
        max-files="20" 
        max-size="204800" 
        placeholder="<div class='text-center !text-2xl text-primary dark:text-primary-600'>Arrastra y suelta o <span class='filepond--label-action dark:!decoration-primary-600'> Subir archivos</span></div>"
        
        />
    </div>

    @error('document')
        <div class="mb-4 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
    @enderror

</div>