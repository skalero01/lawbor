<div class="mt-1 flex rounded-md shadow-sm">
	@isset($group->before)
  		@foreach($group->before as $text)
  			<span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-3 text-gray-500 dark:text-gray-400 sm:text-sm">{{ $text }}</span>
    	@endforeach
  	@endisset
  	{!! $group->input !!}
  	@isset($group->after)
  		@foreach($group->after as $text)
  			<span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-3 text-gray-500 dark:text-gray-400 sm:text-sm">{{ $text }}</span>
    	@endforeach
  	@endisset
</div>
