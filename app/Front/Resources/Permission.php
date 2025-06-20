<?php

namespace App\Front\Resources;

use App\Models\Permission as Model;
use WeblaborMx\Front\Inputs;

class Permission extends Resource
{
    public $base_url = '/admin/permissions';
    public $model = Model::class;
    public $title = 'name';
    public $showOnMenu = false;

    public function fields()
    {
        return [
            Inputs\ID::make(),
            Inputs\Text::make('Name')->rules('required'),
            Inputs\Text::make('Guard Name')->rules('required')
        ];
    }
}
