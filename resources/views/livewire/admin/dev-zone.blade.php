<div class="space-y-5" x-data="{
    wireTextarea: {
        ['x-init']() {
            const checkInitialized = () => setTimeout(() => {
                if (!this.$el.nextElementSibling?.id?.startsWith('codeeditor-')) return checkInitialized();
                this.$nextTick(() => {
                    const editor = ace.edit(this.$el.nextElementSibling);
                    editor.on('change', () => {
                        this.$el.value = editor.getValue();
                        this.$el.dispatchEvent(new Event('input'));
                    });
                })
            }, 200);
            checkInitialized();
        }
    }
}">
    <div class="flex justify-between">
        <div>
            <h1 class="font-mono text-3xl font-bold">Dev Zone</h1>
            <p class="text-sm text-gray-500">
                {{ config('app.name') }} ({{ $currentCommit }})
            </p>
        </div>
        <div>
            <x-button icon="external-link" href="/logs" target="_blank">
                @lang('Check logs')
            </x-button>
        </div>
    </div>
    <x-card :title="__('Deployment')">
        <div>
            <x-label for="afterDeployCommands">Execute after pull</x-label>
            <div class="rounded-lg py-4 shadow" style="background-color: #272822" wire:ignore>
                <textarea wire:model="afterDeployCommands"
                    x-bind="wireTextarea"
                    class="w-full resize-none border-0 bg-transparent font-mono text-xs text-white focus:ring-0"
                    data-type="codeeditor"
                    data-lang="powershell"
                    data-color="black"
                    name="afterDeployCommands"
                    autocomplete="off">{{ $afterDeployCommands }}</textarea>
            </div>
        </div>
        <x-slot name="footer">
            <div class="flex justify-end">
                <button wire:click="deploy" class="inline-flex items-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" style="">
                    <x-icon name="terminal" class="mr-2 h-5 w-5" />
                    {{ __('Run deploy') }}
                </button>
            </div>
        </x-slot>
    </x-card>

    <x-card :title="__('Env File')">
        <div>
            <div class="rounded-lg py-4 shadow" style="background-color: #272822" wire:ignore>
                <textarea wire:model="env"
                    x-bind="wireTextarea"
                    class="w-full resize-none border-0 bg-transparent font-mono text-xs text-white focus:ring-0"
                    data-type="codeeditor"
                    data-lang="powershell"
                    data-color="black"
                    name="env"
                    autocomplete="off">{{ $env }}</textarea>
            </div>
        </div>
        <x-slot name="footer">
            <div class="flex justify-end">
                <button class="ml-3 inline-flex items-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                    wire:click="changeEnv">
                    <x-icon name="pencil-alt" class="mr-2 h-5 w-5" />
                    {{ __('Apply changes') }}
                </button>
            </div>
        </x-slot>
    </x-card>
</div>

@section('footer')
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="https://weblabormx.github.io/Easy-JS-Library/library/script.js"></script>
@endsection
