<?php

namespace App\Enums;

trait IsEnum
{
    public function is($name): bool
    {
        return $this === self::{$name};
    }

    public static function options()
    {
        return collect(self::cases())->mapWithKeys(fn(self $status) => [
            $status->value => $status->label()
        ]);
    }
}
