<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Permission\Models\Role as Model;
use WeblaborMx\TallUtils\Models\WithActivityLog;

class Role extends Model
{
	use SearchableTrait, SoftDeletes, WithActivityLog;

    protected $guarded = [];
    protected $searchable = [
        'columns' => [
            'name' => 30,
        ],
    ];

    /*
     * Attributes
     */

    public function getTitleAttribute()
    {
        return __(ucfirst($this->name));
    }
}
