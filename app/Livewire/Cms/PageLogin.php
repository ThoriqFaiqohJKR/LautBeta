<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PageLogin extends Component
{
    public string $email = '';
    public string $password = '';

    public function login()
    {
        $ok = Auth::attempt([
            'email'    => $this->email,
            'password' => $this->password,
        ]);

        if (!$ok) {
            $this->addError('email', 'Email atau password salah.');
            return;
        }

        session()->regenerate();


        $user   = Auth::user();
        $locale = app()->getLocale();
        $target = $user->role === 'admin'
            ? "/cms/{$locale}/pageabout"
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
                ? "/cms/{$locale}/pageabout"
                : '/';

            return redirect()->to($target);
        }
    }
}
