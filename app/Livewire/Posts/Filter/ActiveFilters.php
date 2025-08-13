<?php

namespace App\Livewire\Posts\Filter;

use Livewire\Component;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;

class ActiveFilters extends Component
{
    public $categories;
    public $tags;
    public $users;
    public $currentSearch = '';
    public $currentCategory = '';
    public $currentTag = '';
    public $currentAuthor = '';

    protected $listeners = [
        'searchUpdated' => 'updateSearch',
        'categorySelected' => 'updateCategory',
        'tagSelected' => 'updateTag',
        'authorSelected' => 'updateAuthor',
        'filtersCleared' => 'clearFilters',
    ];

    public function mount()
    {
        $this->categories = Category::all();
        $this->tags = Tag::all();
        $this->users = User::all();

        // Load current filter state from URL
        $this->currentSearch = request('search', '');
        $this->currentCategory = request('category', '');
        $this->currentTag = request('tag', '');
        $this->currentAuthor = request('author', '');
    }

    public function updateSearch($search)
    {
        $this->currentSearch = $search;
    }

    public function updateCategory($category)
    {
        $this->currentCategory = $category;
    }

    public function updateTag($tag)
    {
        $this->currentTag = $tag;
    }

    public function updateAuthor($author)
    {
        $this->currentAuthor = $author;
    }

    public function clearFilters()
    {
        $this->currentSearch = '';
        $this->currentCategory = '';
        $this->currentTag = '';
        $this->currentAuthor = '';
    }

    public function removeFilter($filterType)
    {
        switch ($filterType) {
            case 'search':
                $this->currentSearch = '';
                break;
            case 'category':
                $this->currentCategory = '';
                break;
            case 'tag':
                $this->currentTag = '';
                break;
            case 'author':
                $this->currentAuthor = '';
                break;
        }

        $this->dispatch('filterRemoved', $filterType);
    }

    public function render()
    {
        return view('livewire.posts.filter.active-filters');
    }
}
