@php
	if($button->type=='btn-primary') {
		$classes = "ml-3 inline-flex items-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800";
	} else if($button->type=='btn-secondary') {
		$classes = "ml-3 inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800";
	} else if($button->type=='btn-danger') {
		$classes = "ml-3 inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800";
	} else if($button->type=='btn-outline-danger') {
		$classes = "ml-3 inline-flex items-center rounded-md border border-transparent text-red-600 dark:text-red-400 px-4 py-2 text-sm font-medium bg-white dark:bg-gray-700 shadow-sm hover:text-red-700 dark:hover:text-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 border border-red-700 dark:border-red-500";
	}
@endphp
<a href="{{$button->link}}" type="button" class="{{$classes ?? ''}} {{$button->class}}" style="{{$button->style}}" {!! $button->extra !!}>
    {!! $button->text !!}
</a>