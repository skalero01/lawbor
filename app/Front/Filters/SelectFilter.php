<?php

namespace App\Front\Filters;

use WeblaborMx\Front\Inputs\Select;

class SelectFilter extends TextFilter
{
	public $options, $multiple = false;

    public function field()
    {
        $field =  Select::make($this->title, $this->slug)->options($this->options);
        if($this->multiple) {
            $field = $field->multiple();
        }
        return $field;
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }
}
