<?php

namespace App\Livewire\Posts\Filter;

use Livewire\Component;
use App\Models\Category;

class CategoriesFilter extends Component
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

    public function updateCategory($category)
    {
        $this->currentCategory = $category;
    }

    public function updateSearch($search)
    {
        $this->currentSearch = $search;
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
        $this->currentTag = '';
        $this->currentAuthor = '';
    }

    public function handleFilterRemoved($filterType)
    {
        switch ($filterType) {
            case 'search':
                $this->currentSearch = '';
                break;
            case 'tag':
                $this->currentTag = '';
                break;
            case 'author':
                $this->currentAuthor = '';
                break;
        }
    }

    public function selectCategory($categorySlug)
    {
        $this->dispatch('categorySelected', $categorySlug);
    }

    public function render()
    {
        // Calculate category counts based on current filters (excluding category filter)
        $categories = Category::withCount(['posts' => function ($query) {
            $query->where('is_published', true);

            // Apply current filters except category
            if ($this->currentSearch) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->currentSearch}%")
                        ->orWhere('excerpt', 'like', "%{$this->currentSearch}%");
                });
            }

            if ($this->currentTag) {
                $query->whereHas('tags', function ($q) {
                    $q->where('slug', $this->currentTag);
                });
            }

            if ($this->currentAuthor) {
                $query->where('user_id', $this->currentAuthor);
            }
        }])->orderBy('name')->get();

        return view('livewire.posts.filter.categories-filter', compact('categories'));
    }
}
