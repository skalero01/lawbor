@if(count($cards)>0)
	<div class="row">
	    <div class="d-flex col-xl-12 align-items-stretch">
	        <div class="card d-flex w-100 mb-4 dark:bg-gray-800 dark:border-gray-700">
	            <div class="row no-gutters row-bordered h-100 dark:border-gray-700">
	                @foreach($cards as $card)
	                    {!! $card->html() !!}
	                @endforeach
	            </div>
	        </div>
	    </div>
	</div>
@endif