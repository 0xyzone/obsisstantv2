<?php

namespace App\Livewire;

use App\Models\Tournament;
use App\Models\TournamentUser;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class StartingSoon extends Component
{
    public $user = '';
    public $tournament = '';

    public function mount($user)
    {
        $this->user = User::find($user);
        $this->tournament = $this->user->tournaments->where('is_active', true)->first();
    }


    #[Title('Starting Soon')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.starting-soon');
    }
}
