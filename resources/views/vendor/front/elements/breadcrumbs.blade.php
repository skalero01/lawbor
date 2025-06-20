<nav class="hidden px-5 py-2 mb-7 w-max bg-white dark:bg-gray-800 rounded-md shadow-sm sm:flex" aria-label="Breadcrumb">
    <ol role="list" class="flex items-center space-x-3">
        <li class="relative">
            <a href="/admin" class="text-sm font-medium text-gray-400 transition-colors hover:text-gray-500">
                <span class="sr-only">{{ __('Home') }}</span>
                <span>
                    <svg class="w-5 h-5 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                </span>
            </a>
        </li>
        @isset($slot)
            {{ $slot }}
        @endisset
        @isset($front)
            @foreach ($front->getBreadcrumbs($object ?? null, $data ?? null) as $breadcrumb)
                <li
                    class="relative before:absolute before:top-0 before:-translate-x-full before:left-0 before:content-['/'] before:dark:text-gray-400 pl-2">
                    <span
                        class="text-sm font-medium text-gray-500 dark:text-gray-400 transition-colors hover:text-gray-700 dark:hover:text-gray-300">{!! $breadcrumb['html'] !!}</span>
                </li>
            @endforeach
        @endisset
    </ol>
</nav>
