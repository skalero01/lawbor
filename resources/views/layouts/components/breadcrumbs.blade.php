<nav class="hidden md:flex mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
        @isset($breadcrumb)
            @foreach($breadcrumb as $breadcrumbItem)
                @if ($loop->first)
                    <li>
                        <div class="flex items-center text-primary">
                            <svg class="w-5 h-5 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            <a href="{{ isset($breadcrumbItem['url']) ? $breadcrumbItem['url'] : '#' }}" class="ml-1 text-primary hover:text-primary-600 md:ml-2 dark:text-primary dark:hover:text-primary-600">{{ $breadcrumbItem['label'] }}</a>
                        </div>
                    </li>
                @elseif (!$loop->last)
                    <li>
                        <div class="flex items-center text-primary">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ $breadcrumbItem['url'] }}" class="ml-1 text-primary hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-primary-600">{{ $breadcrumbItem['label'] }}</a>
                        </div>
                    </li>
                @else
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 defaulttextcolor md:ml-2 dark:text-gray-500">{{ $breadcrumbItem['label'] }}</span>
                        </div>
                    </li>
                @endif
            @endforeach
        @endif
    </ol>
</nav>
