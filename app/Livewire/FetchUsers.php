<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class FetchUsers extends Component
{
    use WithPagination;



    public function mount()
    {
        // Initialize users as empty array
        // $this->users = [];
    }



    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            $this->resetPage();
            // Optionally emit an event to refresh other components
            $this->dispatch('user-deleted');
        }
    }

    #[On('reset-users')]
    public function render()
    {
        $users = User::paginate(2);

        return view('livewire.fetch-users', compact('users'));
    }
}
