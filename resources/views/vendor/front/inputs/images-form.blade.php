<div class="card text-white bg-dark dark:bg-gray-800 mb-3 text-center">
    <div class="card-body">
        <button type="button" class="btn btn-secondary dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white" onclick="executeFileMultiple('{{ $id }}')">{{ __('Upload Images') }}</button>
        {{ html()->file($input->column . '[]')->id($id)->style('display:none;')->multiple() }}
    </div>
</div>

@pushonce('scripts-footer')
    <script>
        function executeFileMultiple(id) {
            $('#' + id).click();
        };
    </script>
@endpushonce
