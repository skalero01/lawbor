<x-mail::message>
# {{ __('Hi :name', ['name' => $userName]) }}


{{ $newRol != null ? __(':newRol role added', ['newRol' => $newRol]) : '' }} 

{{ $oldRol != null ? __(':oldRol role was deleted', ['oldRol' => $oldRol]) : '' }}

{{ __('Thanks') }},<br>
{{ config('app.name') }}
</x-mail::message>
