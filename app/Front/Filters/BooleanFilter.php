<?php

namespace App\Front\Filters;

use WeblaborMx\Front\Inputs\Select;

class BooleanFilter extends TextFilter
{
	public $true_value = 1;

    public function field()
    {
        return Select::make($this->title, $this->slug)->options([
            0 => __('False'),
            $this->true_value => __('True')
        ]);
    }

    public function setTrueValue($value)
    {
    	$this->true_value = $value;
    	return $this;
    }
}
