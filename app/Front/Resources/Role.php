<?php

namespace App\Front\Resources;

use App\Front\Inputs as Custom;
use App\Models\Role as Model;
use WeblaborMx\Front\Inputs;

class Role extends Resource
{
    public $base_url = '/admin/roles';
    public $model = Model::class;
    public $title = 'title';
    public $icon = 'shield-check';

    public function fields()
    {
        return [
            Inputs\ID::make(),
            Inputs\Text::make('Title')->exceptOnForms(),
            Inputs\Text::make('Name')->rules('required'),
            Inputs\Text::make('Guard Name')->rules('required')->default('web'),
            Custom\PermissionSelector::make('Permission'),
        ];
    }

    public function update($object, $request)
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
