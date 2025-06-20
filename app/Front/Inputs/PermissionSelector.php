<?php

namespace App\Front\Inputs;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use WeblaborMx\Front\Inputs\Input;
use WeblaborMx\Front\Traits\InputRelationship;

class PermissionSelector extends Input
{
    use InputRelationship;

    public $relation, $model_name, $relation_front, $search_field, $empty_title;
    public $show_placeholder = true;
    public $show_on_create = false;
    public $show_on_index = false;

    protected ?string $guardName = null;

    public function __construct($title, $relation = null, $model_name = null, $source = null)
    {
        $relation = str($relation)->snake();
        $this->relation = $relation->plural()->toString();
        $this->source = $source;
        $this->model_name = $model_name ?? $title;
        $this->relation_front = getFront($this->model_name, $this->source);
        if (! $this->relation_front->canIndex()) {
            $this->show = false;
        }
        $this->title = __('Entity permissions');
        $this->load();
    }

    public function setResource($resource)
    {
        $relation = $this->relation;
        $this->column = $relation . '_mtm';
        if (is_object($resource->object)) {
            $this->default_value = $resource->object->{$this->relation}->pluck('id');
            $this->default_value_force = true;
        }

        return parent::setResource($resource);
    }

    public function processData($data)
    {
        unset($data[$this->column]);
        return $data;
    }

    public function processAfterSave($object, $request)
    {
        $values = $request->{$this->column};
        $object->{$this->relation}()->sync($values);
    }

    public function getValue($object)
    {
        return $this->getView(readonly: true);
    }

    public function form()
    {
        return $this->getView();
    }

    public function fromGuard(string $guardName): self
    {
        $this->guardName = $guardName;

        return $this;
    }

    public function getTitleColumn()
    {
        return $this->search_field ?? $this->relation_front->search_title;
    }

    protected function getView(...$vars)
    {
        $object = $this->resource?->object;
        if (! isset($object)) {
            return;
        }

        $allPermissions = $this->getFromQuery();
        $crudPermissions = $this->filterCrudPermissions($allPermissions);
        $otherPermissions = $allPermissions
            ->filter(fn($v) => ! $crudPermissions->contains($v))
            ->values();
        $column = $this->column;
        $title = $this->getTitleColumn();
        $selected = $this->getSelectedPermissions($object, pluck: 'id');
        $crudHeaders  = $this->getCrudHeader();
        $rand = str()->random(16);

        return view(
            'front.inputs.permission-selector',
            array_merge(compact(
                'rand',
                'column',
                'title',
                'selected',
                'crudHeaders',
                'crudPermissions',
                'otherPermissions',
            ), $vars)
        );
    }

    protected function filterCrudPermissions(Collection $permissions): Collection
    {
        return $permissions
            ->filter(fn($v) => $this->isCrudPermission($v->name))
            ->values();
    }

    protected function isCrudPermission(string $v)
    {
        return in_array(Str::before($v, ' '), array_keys($this->getCrudHeader()));
    }

    public function getCrudHeader()
    {
        return [
            'create' => __('Create'),
            'retrieve' => __('See'),
            'update' => __('Update'),
            'delete' => __('Delete'),
        ];
    }

    protected function getSelectedPermissions($object, ?string $pluck = null): Collection
    {
        $relation = $this->relation;
        if (is_null($relation)) {
            return collect();
        }

        $query = $object->{$relation}();

        if ($pluck) {
            return $query->pluck($pluck);
        }
        return $query->get();
    }

    protected function getFromQuery(): Collection
    {
        $model = $this->relation_front->getModel();
        $model = new $model;

        if (isset($this->force_query)) {
            $force_query = $this->force_query;
            $query = $force_query($model);
        } else {
            $query = $this->relation_front->globalIndexQuery();
        }

        if (isset($this->filter_query)) {
            $filter_query = $this->filter_query;
            $query = $filter_query($query);
        }

        if (! is_null($this->guardName)) {
            $query = $query->where('guard_name', $this->guardName);
        }

        $options = $query->get();

        if (isset($this->filter_collection)) {
            $filter_collection = $this->filter_collection;
            $options = $filter_collection($options);
        }

        return $options;
    }
}
