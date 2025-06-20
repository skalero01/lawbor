<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Layout Name
    |--------------------------------------------------------------------------
    |
    | The default layout name that uses Laravel Front
    |
    */

    'default_layout' => 'layouts.front',

    /*
    |--------------------------------------------------------------------------
    | Datetime Wrap
    |--------------------------------------------------------------------------
    |
    | If you want to wrap the datetime inputs on a carbon macro
    | This is useful if your user have a custom timezone
    |
    */

    'datetime_wrap' => 'toUserTimezone',
];
