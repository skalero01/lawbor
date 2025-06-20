<?php

use App\Livewire;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Here are defined all the authentication & authorization routes based on
| Laravel UI scaffolding, converted to Livewire components.
|
*/

Route::middleware('guest')->group(function () {
    Route::get('login', Livewire\Auth\Login::class)->name('login');
    Route::get('register', Livewire\Auth\Register::class)->name('register');
    Route::get('terms', Livewire\Auth\TermsAndConditions::class)->name('terms');
    Route::get('password/reset', Livewire\Auth\ForgotPassword::class)->name('password.request');
    Route::get('password/reset/{token}', Livewire\Auth\ResetPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('password/confirm', Livewire\Auth\ConfirmPassword::class)->name('password.confirm');
    Route::get('email/verify', Livewire\Auth\Verification::class)->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', Livewire\Auth\Verification::class)->name('verification.verify');

    Route::get('password/request', Livewire\Auth\PasswordRequest::class)->name('password.insecure.request');

    Route::get('logout', function (Request $request) {
        auth()->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::get('/stop-acting', function () {
        /** @var \App\Models\User */
        $user = auth()->user();
        $user->stopActingAs();
        return redirect(RouteServiceProvider::HOME);
    })->name('stop-acting');
});
