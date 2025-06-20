<dl class="row">
    @foreach($horizontal_description->data as $title => $value)
        <dt class="col-sm-2 dark:text-white">{!! $title !!}</dt>
        <dd class="col-sm-10 dark:text-gray-300">{!! $value !!} </dd>
    @endforeach
</dl>