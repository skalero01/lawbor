<?php

namespace App\Notifications\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreatedUserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public User $user,
        public ?string $password = null,
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject($this->subject())
            ->greeting($this->subject())
            ->line($this->description())
            ->line(__('Your email to login is: **:email**', ['email' => $this->user->email]));

        if ($this->password) {
            $message->line(__('Your password is: **:password**', ['password' => $this->password]));
        }

        return $message->action(__('Login'), route('login'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->subject(),
            'description' => $this->description(),
            'image' => auth()->user()->avatar,
            'icon' => 'inbox',
            'color' => 'bg-primary-700',
            'url' => null,
            'target' => null,
        ];
    }

    private function subject()
    {
        return __('Welcome to :name', ['name' => config('app.name')]);
    }

    private function description()
    {
        return __('We are glad to have you with us!');
    }
}
