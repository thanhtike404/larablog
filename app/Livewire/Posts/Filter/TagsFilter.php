<?php

namespace App\Livewire\Posts\Filter;

use Livewire\Component;
use App\Models\Tag;

class TagsFilter extends Component
{
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

    public function mount()
    {
        $this->currentSearch = request('search', '');
        $this->currentCategory = request('category', '');
        $this->currentTag = request('tag', '');
        $this->currentAuthor = request('author', '');
    }

    public function updateTag($tag)
    {
        $this->currentTag = $tag;
    }

    public function updateSearch($search)
    {
        $this->currentSearch = $search;
    }

    public function updateCategory($category)
    {
        $this->currentCategory = $category;
    }

    public function updateAuthor($author)
    {
        $this->currentAuthor = $author;
    }

    public function clearFilters()
    {
        $this->currentSearch = '';
        $this->currentCategory = '';
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
            case 'author':
                $this->currentAuthor = '';
                break;
        }
    }

    public function selectTag($tagSlug)
    {
        $this->dispatch('tagSelected', $tagSlug);
    }

    public function render()
    {
        // Calculate tag counts based on current filters (excluding tag filter)
        $tags = Tag::withCount(['posts' => function ($query) {
            $query->where('is_published', true);

            // Apply current filters except tag
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

            if ($this->currentAuthor) {
                $query->where('user_id', $this->currentAuthor);
            }
        }])->orderBy('name')->get();

        return view('livewire.posts.filter.tags-filter', compact('tags'));
    }
}
