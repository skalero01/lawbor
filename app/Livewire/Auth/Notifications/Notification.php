<?php

namespace App\Livewire\Auth\Notifications;

use App\Models\{DatabaseNotification};
use Livewire\Component;

class Notification extends Component
{
    public function markAsRead(DatabaseNotification $notification)
    {
        if($notification->is_read) {
            return;
        }
        $notification->markAsRead();
    }

    public function render()
    {
        if(! is_object(auth()->user())) {
            $notifications = [];
            $totalNotification = 0;
        } else {
            $notifications = auth()->user()->notifications()->limit(5)->get();
            $totalNotification = auth()->user()->unreadNotifications()->count();
        }

        return view('livewire.auth.notifications.notification', compact('notifications', 'totalNotification' ));
    }
}
