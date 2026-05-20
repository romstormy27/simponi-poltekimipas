<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Form;

class LoginForm extends Form
{
    // 🟢 1. Ganti Properti dari $email menjadi $username
    public string $username = '';
    public string $password = '';
    public bool $remember = false;

    /**
     * Aturan validasi form login.
     */
    public function rules(): array
    {
        return [
            // 🟢 2. Ubah dari 'email' => ['required', 'string', 'email'] menjadi username string biasa
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Eksekusi proses autentikasi ke database.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 🟢 3. Ubah key array 'email' menjadi 'username' pada Auth::attempt
        if (! Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                // Diarahkan ke form.username karena menggunakan Form Object Livewire
                'form.username' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Memastikan request login tidak terkena rate limit (brute force protection).
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Ambil throttling rate limiter key.
     */
    protected function throttleKey(): string
    {
        // 🟢 4. Ubah dari $this->email menjadi $this->username
        return Str::transliterate(Str::lower($this->username).'|'.request()->ip());
    }
}