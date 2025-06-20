<?php

namespace App\Front\Filters;

use Illuminate\Support\Str;
use WeblaborMx\Front\Inputs\Text;

class TextFilter extends Filter
{
	public $slug, $field, $title, $scope, $operator = '=';

    public function apply($query, $value)
    {
        if(isset($this->scope)) {
            return $query->{$this->scope}($value);
        }
        return $query->where($this->field, $this->operator, $value);
    }

    public function field()
    {
        return Text::make($this->title, $this->slug);
    }

    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function setTitle($title, $field = null, $slug = null)
    {
        $this->title = $title;
        if(is_null($slug)) {
            $this->slug = Str::slug($title, '_');
        } else {
            $this->slug = $slug;
        }
        if(is_null($field)) {
            $this->field = $this->slug;
        } else {
            $this->field = $field;
        }
        return $this;
    }

    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }
}
