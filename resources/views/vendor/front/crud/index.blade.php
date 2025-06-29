@extends('front::layout')

@section('content')

    <!-- This example requires Tailwind CSS v2.0+ -->
    @include('front::elements.breadcrumbs')

    <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">{{$front->plural_label}}</h2>
        </div>
        <div class="mt-4 flex flex-shrink-0 md:mt-0 md:ml-4">
            @foreach($front->getIndexLinks() as $button)
                {!! $button->form() !!}
            @endforeach
        </div>
    </div>

    @if(count($front->filters())>0)
        <div class="mt-6 font-bold dark:text-white">{{ __('FILTER RESULTS', ['name' => strtoupper($front->plural_label)]) }}</div>
        {!! Form::open(['url' => request()->url(), 'method' => 'get', 'class' => 'mt-2 mb-4 flex gap-6 bg-slate-50 dark:bg-slate-800 p-4 border border-gray-200 dark:border-gray-700 rounded-lg']) !!} 
            {!! Form::hidden($front->getCurrentViewRequestName()) !!}
            @foreach($front->getFilters() as $filter)
                {!! $filter->formHtml() !!}
            @endforeach
            <div>
                {!! Form::submit(__('Search'), ['class' => 'bg-primary-600 text-white px-4 rounded mt-6 py-2 cursor-pointer']) !!}
            </div>
        {{ html()->form()->close() }}
    @endif

    @if($front->getLenses()->count() > 1)
        <div class="flex items-center mt-6">
            <h4 class="text-lg font-medium dark:text-white">Lenses</h4>
            @foreach($front->getLenses() as $button)
                {!! $button->form() !!}
            @endforeach
        </div>
    @endif

    @include ('front::components.cards', ['cards' => $front->cards()])
    @include ($front->getCurrentView())

@endsection

