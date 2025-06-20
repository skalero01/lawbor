<?php

namespace App\Front\Inputs;

use Illuminate\Support\Facades\Blade;
use WeblaborMx\Front\Inputs\Input;

class Password extends Input
{
    public $show_on_index = false;
    public $show_on_show = false;

    public function form()
    {
        $column = $this->column;
        $id = rand(10000, 99999);

        return Blade::render(<<<HTML
        <div x-data="input{{ \$id }}">
            <x-password :name="\$column" x-ref="input">
                <x-slot name="prepend">
                    <div class="flex absolute inset-y-0 right-8 items-center p-0.5">
                        <x-button
                            class="h-full"
                            icon="arrow-path"
                            primary
                            flat
                            sm
                            squared
                            x-on:click="generate"
                            title="{{ __('Generate Password') }}"
                        />
                    </div>
                </x-slot>
            </x-input>
        </div>
        <script>
            window.addEventListener('alpine:init', () => {
                Alpine.data("input{{ \$id }}", () => ({
                    generate(){
                        this.\$refs.input.value = this.pass(24);
                    },
                    pass(length) {
                        let result = '';
                        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$^&*()-_{}[]"+/\\\';
                        const charactersLength = characters.length;
                        let counter = 0;
                        while (counter < length) {
                            result += characters.charAt(Math.floor(Math.random() * charactersLength));
                            counter += 1;
                        }
                        return result;
                    }
                }))
            });
        </script>
        HTML, compact('column', 'id'));
    }
}
