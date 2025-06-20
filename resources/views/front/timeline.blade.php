@php $logs = $object->activities()->latest()->with(['causer'])->paginate(10); @endphp
<div class="mt-4">
    <div x-data="{ openLogs: false }" class="mb-4 bg-white dark:bg-gray-800 border rounded-md border-slate-300 dark:border-gray-700">
        <div class="flex items-center justify-between px-4 py-2 border-b cursor-pointer border-slate-300 dark:border-gray-700" @click="openLogs = !openLogs" :class="{ 'border-b border-slate-300 dark:border-gray-700': openLogs }">
            <h4 class="font-medium leading-8 text-slate-400 dark:text-gray-300">{{ __('Activity') }} ({{ $logs->total() }})</h2>
            <x-icon name="chevron-down" class="w-4 h-4 cursor-pointer text-slate-400 hover:text-slate-600" x-bind:class="{ 'rotate-180': openLogs }"/>
        </div>
        <div x-show="openLogs" class="px-4 py-5 rounded-md bg-gray-50 dark:bg-gray-900 sm:p-6" wire:loading.class="opacity-50">
            <div class="flex flex-col gap-4 mb-4">
                @foreach($logs as $log)
                    <div class="p-4 bg-white dark:bg-gray-800 border rounded-md sm:gap-12 sm:flex-row border-slate-300 dark:border-gray-700">
                       
                        <div class="flex flex-col justify-center gap-1">
                            <div class="text-sm break-all">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    @if($log->event)
                                        @lang('Item')
                                        @lang($log->event)
                                    @else
                                        {{ __('Item information updated') }}
                                    @endif
                                    @lang('by')
                                    <a href="/admin/users/{{ $log->causer_id }}"
                                        class="font-medium text-gray-900 dark:text-gray-200">
                                        {{ $log->causer?->name ?? class_basename($log->causer_type) . "($log->causer_id)" }}
                                    </a>
                                </p>
                                @if($log->event)
                                    <code class="mt-2 block text-xs p-2 bg-gray-100 dark:bg-gray-700 text-red-600 dark:text-red-400">{{ $log->changes_text }}</code>
                                @else
                                    <div class="mt-2 block bg-gray-100 dark:bg-gray-700 p-2 dark:text-gray-300">{{ $log->description }}</div>
                                @endif
                                <time class="mt-2 text-gray-400 text-xs block" title="{{ $log->created_at->toUserTimezone()->format('Y-m-d H:i:s') }}">{{ $log->created_at->diffForHumans() }}</time>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="{{ isset($logs->links()->paginator) && $logs->links()->paginator->lastPage() > 1 ? 'mt-5' : 'mt-0' }}">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>