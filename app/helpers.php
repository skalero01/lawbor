<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

if (! function_exists('password_rule')) {
    function password_rule(): Password
    {
        return Password::default()
            ->min(8);
    }
}

if (! function_exists('front_resources')) {
    /**
     * Returns all the registered Front resources
     *
     * @param $prefix Sub-Namespace to get the resources
     * @param $sameLevel exclude the resources that not on the same namespace level
     *
     * @return Collection<int,class-string<\WeblaborMx\Front\Resource>>
     */
    function front_resources(string $prefix = '', bool $sameLevel = false): Collection
    {
        $prefix = class_path(config('front.resources_folder'), '\\', $prefix) . '\\';

        $resources = collect(get_declared_classes())
            ->filter(fn($className) => str_starts_with($className, $prefix))
            ->filter(fn($className) => is_subclass_of($className, \WeblaborMx\Front\Resource::class))
            ->filter(function ($className) {
                $class = new \ReflectionClass($className);
                return $class->isInstantiable();
            });

        if ($sameLevel) {
            $resources = $resources->filter(
                fn($className) =>  ! str_contains(Str::after($className, $prefix), '\\')
            );
        }

        return $resources;
    }
}

if (! function_exists('class_path')) {
    /**
     * @var string[] $paths
     * @return class-string
     */
    function class_path(...$paths): string
    {
        $paths = preg_split('/\\/|\\\/', implode('', $paths));
        $paths = array_map('trim', $paths);
        $paths = array_filter($paths, fn($v) => $v);

        return implode('\\', $paths);
    }
}

if (! function_exists('validateGetThumb')) {
    function validateGetThumb($url)
    {
        return ! Str::startsWith($url, [
            'https://api.dicebear.com',
        ]);
    }
}
