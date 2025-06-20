<?php

namespace App\Front\Filters;

use WeblaborMx\Front\Inputs\Date;

class DateFilter extends TextFilter
{
    public function field()
    {
        return Date::make($this->title, $this->slug);
    }
}
