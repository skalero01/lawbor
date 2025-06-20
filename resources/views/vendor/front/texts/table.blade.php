<table class="table table-striped bg-white dark:bg-gray-800 dark:border-gray-700" style="margin-top: 30px;">
    <thead class="thead-dark dark:bg-gray-700">
        <tr>
        	@foreach($table->headers as $header)
            	<th class="dark:text-white">{!! $header !!}</th>
            @endforeach
        </tr>
    </thead>
    <tbody class="dark:divide-gray-700">
        @foreach($table->data as $column)
            <tr class="dark:border-gray-700">
            	@foreach($column as $value)
                	<td class="dark:text-gray-300">{!! $value !!}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody> 
</table>