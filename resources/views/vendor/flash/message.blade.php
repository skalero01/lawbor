@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
    	@php
   			if($message['level']=='success') {
   				$classes = "text-sm text-green-700 rounded-md bg-green-50 p-4";
   			} else if($message['level']=='danger') {
   				$classes = "text-sm text-red-700 rounded-md bg-red-50 p-4";
   			}
   		@endphp
        <div class="{{$classes ?? ''}} mb-4
                    {{ $message['important'] ? 'alert-important' : '' }}"
                    role="alert"
        >
            @if ($message['important'])
                <button type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-hidden="true"
                >&times;</button>
            @endif

            {!! $message['message'] !!}
        </div>
    @endif
@endforeach

{{ session()->forget('flash_notification') }}
