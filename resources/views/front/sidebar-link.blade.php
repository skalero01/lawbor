@props([
    'name' => '',
    'url' => '#',
    'icon' => 'collection',
    'active' => str()->contains(request()->url(), [$url])
])
<li class="slide">
    <a href="{{ $url }}" class="side-menu__item {{ $active ? 'active' : '' }}">
        <x-icon :name="$icon" class="w-6 h-6 side-menu__icon" />
        <span class="side-menu__label">{{ __($name) }}</span>
    </a>
</li>