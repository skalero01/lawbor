<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoleUpdateNotification extends Notification
{
    use Queueable;

    public function __construct(protected $userName, protected $oldRol, protected $newRol) {}

    public function via(object $notifiable): array
    {
        return $notifiable->channelNotifications();
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Role Update Notification'))
            ->markdown('emails.user.update_role_email', [
                'userName' => $this->userName,
                'oldRol' => $this->oldRol,
                'newRol' => $this->newRol,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        $description = $this->newRol != null ? __(':newRol role added', ['newRol' => $this->newRol]) : '';
        if($description != '' && $this->oldRol != null)
        {
            $description .= ' '.__('and').' ';
        }
        $description .= $this->oldRol != null ? __(':oldRol role was deleted', ['oldRol' => $this->oldRol]) : '';

        return [
            'title' => __('Role Update Notification'),
            'description' => $description,
            'image' => auth()->user()->avatar,
            'icon' => 'inbox',
            'color' => 'bg-primary-700',
            'url' => null,
            'target' => null,
        ];
    }
}
