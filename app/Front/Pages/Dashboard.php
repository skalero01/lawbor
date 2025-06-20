<?php

namespace App\Front\Pages;

use WeblaborMx\Front\Components\Line;
use WeblaborMx\Front\Components\Welcome;

class Dashboard extends Page
{
    public function fields()
    {
        return [
            Welcome::make(),
            Line::make(),
        ];
    }
}
