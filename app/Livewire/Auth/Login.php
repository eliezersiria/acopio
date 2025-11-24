<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;
    protected $rules = ['email' => 'required|email', 'password' => 'required|min:6'];
    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password]))
        {
            session()->regenerate();        
            return $this->redirect('bienvenido', navigate: true);
        }
        session()->flash('message', 'Las credenciales no coinciden');
    }
    public function render()
    {
        return view('livewire.auth.login');
    }
}
