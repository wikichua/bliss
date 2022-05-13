<?php
use Wikichua\Bliss\Http\Livewire\Auth\Register;
use Wikichua\Bliss\Http\Livewire\Auth\AuthenticatedSession;
use Wikichua\Bliss\Http\Livewire\Auth\PasswordResetLink;
use Wikichua\Bliss\Http\Livewire\Auth\NewPassword;
use Wikichua\Bliss\Http\Livewire\Auth\LogoutSession;
use Wikichua\Bliss\Http\Livewire\Auth\EmailVerificationPrompt;
use Wikichua\Bliss\Http\Livewire\Auth\VerifyEmail;
use Wikichua\Bliss\Http\Livewire\Auth\ConfirmablePassword;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->prefix('')->group(function () {
    Route::get('register', Register::class)->name('register');
    Route::get('login', AuthenticatedSession::class)->name('login');
    Route::get('forgot/password', PasswordResetLink::class)->name('password.request');
    Route::get('reset/password/{token}', NewPassword::class)->name('password.reset');
});

Route::middleware('auth')->prefix('')->group(function () {
    Route::get('verify/email', EmailVerificationPrompt::class)->name('verification.notice');
    Route::get('verify/email/{id}/{hash}', VerifyEmail::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::get('confirm-password', ConfirmablePassword::class)->name('password.confirm');
    Route::get('logout/{now?}', LogoutSession::class)->name('logout');
});
