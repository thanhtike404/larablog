<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class ContactForm extends Component
{
    public $email = 'hello';
    public $name = '';
    public $users = [];

    public function mount()
    {
        // Initialize users as empty array
        $this->users = [];
    }

    public function loadUsers()
    {
        sleep(1);

        $this->users = User::all();
        // dd($this->users); // Debugging line to check loaded users
        return $this->users;
    }
    public function placeholder()
    {
        return <<<'HTML'
        <div>
           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <circle cx="12" cy="12" r="10" stroke-width="4" stroke-linecap="round"></circle>
               <path d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
           </svg>
           <span class="ml-2">Loading...</span>
            <svg>...</svg>
        </div>
        HTML;
    }
    public function resetUsers()
    {
        sleep(1);
        $this->users = [];
    }

    public function submit()
    {
        $this->validate([
            'email' => 'required|email',
            'name' => 'required|min:3',
        ]);

        // Here you would typically save the data
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt('password'), // Example password
        ])->save();

        session()->flash('message', 'Form submitted successfully!');

        // Reset form
        $this->reset(['name', 'email']);
        $this->email = 'hello'; // Reset to default
        $this->loadUsers(); // Reload users after submission
    }

    public function render()
    {
        sleep(2);
        return view('livewire.contact-form');
    }
}
