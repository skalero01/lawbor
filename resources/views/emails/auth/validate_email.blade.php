<x-mail::message>
# {{ __('Hi, thanks for you interest on') }} {{ config('app.name') }}

{{ __('Please validate the email address on our site using this code:') }} {{ $code }}

{{ __("If you didn't register on our site you can ignore this message.") }}

{{ __('Thanks') }},<br>
{{ config('app.name') }}
</x-mail::message>
