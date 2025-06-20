<div class="col-span-{{$input->bootstrap_width()}}">
	<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $input->title }}</label>
    {!! $input->form() !!}
    @if(isset($input->help))
	    <small class="text-gray-400 dark:text-gray-500 block text-xs mt-2">{!! $input->help !!}</small>
	@endif
</div>
