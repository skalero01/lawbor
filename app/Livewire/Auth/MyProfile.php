<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class MyProfile extends Component
{
    use WireUiActions, WithFileUploads;

    public User $user;
    public $password = [], $avatar, $locale, $timezone, $notifications = [];

    protected $rules = [
        'user.name' => 'required',
        'user.email' => 'required',
    ];

    public function mount()
    {
        $this->user = auth()->user();
        // Set current language and timezone actual
        $this->locale = auth()->user()->locale;
        $this->timezone = auth()->user()->timezone;
        $this->notifications = [
            'email' => auth()->user()->send_mail,
        ];
    }

    protected function validationAttributes()
    {
        return [
            'password.new' => __('New password'),
            'password.new_confirmation' => __('Confirm password'),
            'user.name' => __('Name'),
            'user.email' => __('Email'),
            'locale' => __('Locale'),
            'timezone' => __('Timezone'),
            'avatar' => __('Profile Photo'),
        ];
    }

    public function changePassword()
    {
        $this->validate([
            'password.new' => 'required|min:8|confirmed',
            'password.new_confirmation' => 'required',
        ]);

        auth()->user()->update([
            'password' => $this->password['new'],
        ]);
        $this->reset('password');
        $this->dialog()->success(__('Sucesss'), __('Password changed correctly'));
    }

    public function updatedAvatar()
    {
        $this->validate([
            'avatar' => 'required|image|max:1024',
        ]);

        $file_name = 'avatars/' . auth()->id() . '_' . time() . '.jpg';

        // Choose the best available driver at runtime.
        $driver = extension_loaded('imagick')
            ? new ImagickDriver()
            : new Driver();

        $manager = new ImageManager($driver);

        $image = $manager->read($this->avatar->get());

        $image->cover(400, 400);

        $disk = 'public';
        Storage::disk($disk)->put($file_name, $image->toJpeg());
        $file_name = Storage::disk($disk)->url($file_name);

        auth()->user()->update([
            'photo' => $file_name,
        ]);
        $this->reset('avatar');
        $this->dialog()->success(__('Sucesss'), __('Avatar updated correctly'));
    }

    public function updateGeneralInformation()
    {
        $this->user->save();
        $this->dialog()->success(__('Sucesss'), __('General information updated correctly'));
    }

    public function updateLocaleAndTimezone()
    {
        $this->validate([
            'locale' => 'required',
            'timezone' => 'required'
        ]);

        auth()->user()->update([
            'locale' => $this->locale,
            'timezone' => $this->timezone,
        ]);

        $this->dialog()->success(__('Sucesss'), __('Language and Timezone updated correctly'));
    }

    public function updateNotifications()
    {
        $this->validate([
            'notifications.email' => 'required|bool',
        ]);
        auth()->user()->update([
            'send_mail' => $this->notifications['email'],
        ]);

        $this->dialog()->success(__('Sucesss'), __('Notifications updated correctly'));
    }

    public function render()
    {
        return view('livewire.auth.my-profile')->extends('layouts.app', [
            'title' => __('Profile'),
            'breadcrumb' => [
                ['label' => __('My Profile'), 'url' => route('auth.profile')]
            ]
        ]);
    }
}
