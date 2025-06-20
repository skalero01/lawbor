<?php

namespace App\Livewire\Auth\Notifications;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 20;

    public function render()
    {
        $notifications = auth()->user()->notifications()->paginate();
        return view('livewire.auth.notifications.index', compact('notifications'))
            ->layout('layouts.app', [
                'title' => __('Profile'),
                'breadcrumb' => [ ['label' => __('Notification Center')]]
            ])
        ;
    }
}
