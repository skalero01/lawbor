<?php

namespace App\Front\Resources;

use WeblaborMx\Front\Resource as Base;

abstract class Resource extends Base
{
    /**
     * Name of the icon to show on the sidebar
     *
     * @see https://heroicons.com/
     * @var string
     */
    public $icon = 'circle-stack';
    public $showOnMenu = true;
    public $section = 'admin';
}
