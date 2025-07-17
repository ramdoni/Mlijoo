<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Login extends Component
{
    public $username;
    public $password;
    public $message;
    public $token;

    public function render()
    {
        return view('livewire.login')
            ->layout('layouts.auth');
    }

    public function login()
    {
        $this->validate(
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => 'Username is required'
            ]
        );


        $captchaIsSuccess = true;
        if (env('APP_ENV') === 'production') {
            $response = Http::post('https://www.google.com/recaptcha/api/siteverify?secret=' . env('CAPTCHA_SITE_SECRET') . '&response=' . $this->token);
            $response = $response->json();

            if (!$response['success']) {
                $this->emit('reload-captcha');
                $this->message = 'Google thinks you are a bot, please refresh and try again';
                $captchaIsSuccess = false;
            }
        }

        if ($captchaIsSuccess) {
            $credentials = ['username' => $this->username, 'password' => $this->password];

            if (Auth::attempt($credentials, $this->remember_me)) {
                return redirect('/dashboard');
            } else {
                $this->message = __('Username / Password incorrect please try again');
            }
        }
    }
}
