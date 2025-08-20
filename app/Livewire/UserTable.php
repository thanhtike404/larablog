<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\On;

class UserTable extends Component
{
    public $perPage = 10;
    public $search = '';
    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
    }
    public function render()
    {
        if ($this->search) {
            $users = User::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->paginate($this->perPage);
        } else {
            $users = User::paginate($this->perPage);
        }

        return view('livewire.user-table', compact('users'));
    }
}
