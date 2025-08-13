<?php

namespace App\Livewire\Posts\Filter;

use Livewire\Component;

class SearchBar extends Component
{
    public $search = '';

    protected $listeners = [
        'filtersCleared' => 'clearSearch',
        'filterRemoved' => 'handleFilterRemoved',
    ];

    public function mount()
    {
        $this->search = request('search', '');
    }

    public function clearSearch()
    {
        $this->search = '';
    }

    public function handleFilterRemoved($filterType)
    {
        if ($filterType === 'search') {
            $this->search = '';
        }
    }

    public function performSearch()
    {
        $this->dispatch('searchUpdated', $this->search);
    }

    public function clearFilters()
    {
        $this->dispatch('filtersCleared');
    }

    public function render()
    {
        return view('livewire.posts.filter.search-bar');
    }
}
