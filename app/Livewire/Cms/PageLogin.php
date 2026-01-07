<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

class PageLogin extends Component
{
    public string $email = '';
    public string $password = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        $this->validate();

        $throttleKey = Str::lower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email', "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.");
            return;
        }

        // Jika pakai guard non-default, ganti Auth::attempt -> Auth::guard('member')->attempt(...)
        $ok = Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], /* remember */ false);

        if (!$ok) {
            RateLimiter::hit($throttleKey, 60); // blok singkat, 60 detik
            $this->addError('email', 'Email atau password salah.');
            $this->password = '';
            return;
        }

        RateLimiter::clear($throttleKey);

        session()->regenerate();

        $user = Auth::user();
        $locale = app()->getLocale();
        $target = $user->role === 'admin'
            ? route('cms.page.about', ['locale' => $locale]) // contoh menggunakan route name
            : '/';

        return redirect()->intended($target);
    }

    public function render()
    {
        return view('livewire.cms.page-login');
    }

    public function mount()
    {
        if (Auth::check()) {
            $locale = app()->getLocale();
            $target = Auth::user()->role === 'admin'
                ? route('cms.page.about', ['locale' => $locale])
                : '/';

            return redirect()->to($target);
        }
    }
}
