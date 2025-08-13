<?php

namespace App\Livewire\Posts\Filter;

use Livewire\Component;
use App\Models\User;

class AuthorsFilter extends Component
{
    public $limit = 5;
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
        'filterRemoved' => 'handleFilterRemoved',
    ];

    public function mount($limit = 5)
    {
        $this->limit = $limit;
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

    public function handleFilterRemoved($filterType)
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
        }
    }

    public function selectAuthor($authorId)
    {
        $this->dispatch('authorSelected', $authorId);
    }

    public function render()
    {
        // Calculate author counts based on current filters (excluding author filter)
        $users = User::withCount(['blogPosts' => function ($query) {
            $query->where('is_published', true);

            // Apply current filters except author
            if ($this->currentSearch) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->currentSearch}%")
                        ->orWhere('excerpt', 'like', "%{$this->currentSearch}%");
                });
            }

            if ($this->currentCategory) {
                $query->whereHas('category', function ($q) {
                    $q->where('slug', $this->currentCategory);
                });
            }

            if ($this->currentTag) {
                $query->whereHas('tags', function ($q) {
                    $q->where('slug', $this->currentTag);
                });
            }
        }])->orderBy('name')->get();

        return view('livewire.posts.filter.authors-filter', compact('users'));
    }
}
