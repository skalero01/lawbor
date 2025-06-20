@props([
    'readonly' => false,
])
<div class="overflow-y-auto w-full">
    <div class="flex flex-col gap-5 w-max" x-data>
        <table class="table">
            <thead>
                <tr>
                    <th class="w-52">
                        <span class="sr-only">@lang('Name')</span>
                    </th>
                    @foreach ($crudHeaders as $name => $header)
                        @php
                            $actionPermissions = $crudPermissions->filter(fn($v) => str_starts_with($v->name, $name));
                            $hasAllSelected = $actionPermissions->filter(fn($v) => !$selected->contains($v->id))->isEmpty();
                        @endphp
                        <th class="px-3 py-1 w-24 cursor-pointer select-none"
                            @if (!$readonly) x-data="{ enabled: @toJs($hasAllSelected) }" x-on:click="enabled = !enabled; $dispatch('toggle-by-category', { enabled: enabled, category: @toJs($name) })" @endif>
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $total = $crudPermissions->count();
                    $added = collect();
                @endphp
                @foreach ($crudPermissions as $row)
                    @php
                        $entity = str($row->name)
                            ->after(' ')
                            ->toString();
                    @endphp
                    @if (!$added->contains($entity))
                        @php $added->add($entity); @endphp
                        <tr>
                            <td class="overflow-hidden py-1.5 overflow-ellipsis whitespace-nowrap cursor-pointer select-none"
                                @if (!$readonly) x-data="{ enabled: false }" x-on:click="enabled = !enabled; $dispatch('toggle-by-entity', { enabled: enabled, entity: @toJs($entity) })" @endif>
                                @lang(str($entity)->headline()->toString())
                            </td>
                            @foreach ($crudHeaders as $category => $_)
                                @php
                                    $permissionName = $category . ' ' . $entity;
                                    $permission = $crudPermissions->firstWhere($title, $permissionName);
                                @endphp
                                @isset($permission)
                                    <td>
                                        <div class="flex justify-center py-1.5">
                                            <input class="form-checkbox transition ease-in-out duration-100 rounded-sm border-secondary-300 text-primary-600 focus:ring-primary-600 focus:border-primary-400 dark:border-secondary-500 dark:checked:border-secondary-600 dark:focus:ring-secondary-600 dark:focus:border-secondary-500 dark:bg-secondary-600 dark:text-secondary-600 dark:focus:ring-offset-secondary-800 w-5 h-5 focus:invalidated:ring-negative-500 invalidated:ring-negative-500 invalidated:border-negative-400 invalidated:text-negative-600 focus:invalidated:border-negative-400 dark:focus:invalidated:border-negative-600 dark:invalidated:ring-negative-600 dark:invalidated:border-negative-600 dark:invalidated:bg-negative-700 dark:checked:invalidated:bg-negative-700 dark:focus:invalidated:ring-offset-secondary-800 dark:checked:invalidated:border-negative-700" 
                                                type="checkbox" 
                                                name="permissions_mtm[]" 
                                                value="{{ $permission['id'] }}" 
                                                @if($selected->contains($permission['id'])) checked @endif
                                                data-category="{{ $category }}"
                                                data-entity="{{ $entity }}"
                                                x-on:toggle-by-entity.window="() =&gt; {
                                                    if($event.detail.entity !== $el.dataset.entity) return;

                                                    $el.checked = $event.detail.enabled;
                                                    $el.dispatchEvent(new Event('input'));
                                                }" 
                                                x-on:toggle-by-category.window="() =&gt; {
                                                    if($event.detail.category !== $el.dataset.category) return;

                                                    $el.checked = $event.detail.enabled;
                                                    $el.dispatchEvent(new Event('input'));
                                                }"
                                                x-tooltip.raw.placement.right="{{ $permission['name'] }}"
                                                id="permissions_mtm[]">
                                        </div>
                                    </td>
                                @else
                                    <td></td>
                                @endisset
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <p class="block text-sm font-medium text-gray-700 cursor-default">@lang('Permissions')</p>

        <table class="table">
            <thead>
                <tr>
                    <th class="w-52"><span class="sr-only">@lang('Name')</span></th>
                    <th class="w-24"><span class="sr-only">@lang('Enabled')</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($otherPermissions as $permission)
                    <tr>
                        <td class="overflow-hidden w-52 overflow-ellipsis whitespace-nowrap select-none">
                            <label for="{{ $permission->name }}" class="block py-1.5 cursor-pointer select-none">
                                @lang(
                                    str($permission->name)->headline()->toString()
                                )
                            </label>
                        </td>
                        <td>
                            <div class="flex justify-center py-1.5">
                                <x-checkbox name="{{ $column }}[]"
                                    :value="$permission->id"
                                    :checked="$selected->contains($permission->id)"
                                    :id="$permission->name"
                                    :disabled="$readonly"
                                    x-tooltip.raw.placement.right="{{ $permission->name }}"
                                    md />
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>