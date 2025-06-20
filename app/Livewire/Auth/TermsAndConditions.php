<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class TermsAndConditions extends Component
{
    public function render()
    {
        return view('livewire.auth.terms-and-conditions')->layout('layouts.auth');
    }
}
