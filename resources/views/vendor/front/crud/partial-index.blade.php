@php $helper = $front->getPartialIndexHelper($result, $pagination_name ?? null, $show_filters ?? null); @endphp

@if ($result->count() > 0)
    <div class="overflow-x-auto -mx-4 mt-6 ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 shadow sm:-mx-6 md:mx-0 md:rounded-lg" style="{{ $style ?? '' }}">
        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    @foreach ($helper->headers() as $field)
                        <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-200 sm:pl-6 {{ $field->class }}">{{ $field->title }}</th>
                    @endforeach
                    @if ($helper->show_actions)
                        <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-6">
                            <span class="sr-only">@lang('Edit')</span>
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($helper->rows() as $row)
                    <tr>
                        @foreach ($row->columns as $field)
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 dark:text-gray-400 sm:pl-6 {{ $field->class }}">
                                {!! $field->value !!}
                            </td>
                        @endforeach
                        @if ($helper->show_actions)
                            @include('front::elements.object_actions', ['base_url' => $front->getBaseUrl(), 'object' => $row->object])
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pt-2 text-gray-500 dark:text-gray-400">
        {{ $helper->views() }}
        {{ $helper->totals() }}
        {{ $helper->filters() }}
    </div>
    @if($helper->links()!==null && $helper->links()->paginator->hasPages())
        <div class="mt-4">
            {{ $helper->links() }}
        </div>
    @endif
@else
    <div class="py-20 mt-4 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 md:rounded-lg">
        {{ __('No data to show') }}
    </div>
@endif
