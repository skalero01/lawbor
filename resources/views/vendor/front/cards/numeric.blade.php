<div class="col-sm-{{$card->bootstrap_width()}}">
    <div class="card-body media align-items-center dark:bg-gray-800 dark:text-white" style="{{$card->getStyle()}}">
        @if(!is_null($card->getIcon()))
            @if(!is_null($card->link()))
                <a href="{{$card->link()}}">
            @endif
            <i class="{{$card->getIcon()}} display-4 text-primary dark:text-primary-400"></i>
            @if(!is_null($card->link()))
                </a>
            @endif
        @endif
        <span class="media-body d-block ml-3">
            <span class="text-big">
            	<span class="font-weight-bolder">{!! $card->showNumber($card->getNumber()) !!}</span>
            	{!! __($card->getSubtitle()) !!}
            </span><br>
            <small class="float-right">
                @if($card->getPorcentage() > 0)
                    <i class='fa fa-arrow-circle-up text-success'></i> {{$card->getPorcentage()}}% Increase
                @elseif($card->getPorcentage() < 0)
                    <i class='fa fa-arrow-circle-down text-danger'></i> {{($card->getPorcentage())*-1}}% Decrease
                @endif
            </small>
            <small class="text-muted dark:text-gray-400">{!! __($card->getText()) !!}</small>
        </span>
    </div>
</div>
