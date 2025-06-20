<?php

namespace App\Livewire\Auth\Traits;

use App\Mail\Auth\ValidateEmail;
use Illuminate\Support\Facades\Mail;

trait NeedsVerification
{
    public $code, $user_code, $afterFunction, $save, $view = 'normal';

    public function verifyEmail($afterFunction, $save = true)
    {
        $this->afterFunction = $afterFunction;
        $this->save = $save;
        $this->code = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 10)), 0, 10);
        Mail::to($this->user->email)->send(new ValidateEmail($this->user->full_name, $this->code));
        $this->view = 'verify-email';
    }

    public function validateCode()
    {
        $this->validate([
            'user_code' => 'required|same:code',
        ]);
        $this->user->email_verified_at = now();
        if($this->save) {
            $this->user->save();
        }
        $function = $this->afterFunction;
        $this->$function();
        $this->view = 'normal';
    }

}
