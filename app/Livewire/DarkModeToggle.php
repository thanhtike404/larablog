<?php

namespace App\Livewire;

use Livewire\Component;

class DarkModeToggle extends Component
{
    public $darkMode = false;

    public function mount()
    {
        // Initialize from session, but JavaScript will handle the actual theme detection
        $this->darkMode = session('darkMode', false);
    }

    public function syncTheme($isDark)
    {
        $this->darkMode = $isDark;
        session(['darkMode' => $this->darkMode]);
    }

    public function toggleDarkMode()
    {
        $this->darkMode = !$this->darkMode;
        session(['darkMode' => $this->darkMode]);
        $this->dispatch('dark-mode-toggled', $this->darkMode);
    }

    public function render()
    {
        return view('livewire.dark-mode-toggle');
    }
}
