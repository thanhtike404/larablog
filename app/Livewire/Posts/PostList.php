<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlogPost;

class PostList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $tag = '';
    public $author = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'tag' => ['except' => ''],
        'author' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'filterUpdated' => 'updateFilters',
        'filtersCleared' => 'clearAllFilters',
        'searchUpdated' => 'updateSearch',
        'categorySelected' => 'updateCategory',
        'tagSelected' => 'updateTag',
        'authorSelected' => 'updateAuthor',
        'filterRemoved' => 'removeFilter',
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->category = request('category', '');
        $this->tag = request('tag', '');
        $this->author = request('author', '');
    }

    public function updateFilters($filters)
    {
        $this->search = $filters['search'] ?? '';
        $this->category = $filters['category'] ?? '';
        $this->tag = $filters['tag'] ?? '';
        $this->author = $filters['author'] ?? '';
        $this->resetPage();

        // Dispatch the current state to other components
        $this->dispatch('filtersChanged', [
            'search' => $this->search,
            'category' => $this->category,
            'tag' => $this->tag,
            'author' => $this->author,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingTag()
    {
        $this->resetPage();
    }

    public function updatingAuthor()
    {
        $this->resetPage();
    }

    public function updateSearch($search)
    {
        $this->search = $search;
        $this->resetPage();
    }

    public function updateCategory($category)
    {
        $this->category = $category;
        $this->resetPage();
    }

    public function updateTag($tag)
    {
        $this->tag = $tag;
        $this->resetPage();
    }

    public function updateAuthor($author)
    {
        $this->author = $author;
        $this->resetPage();
    }

    public function removeFilter($filterType)
    {
        switch ($filterType) {
            case 'search':
                $this->search = '';
                break;
            case 'category':
                $this->category = '';
                break;
            case 'tag':
                $this->tag = '';
                break;
            case 'author':
                $this->author = '';
                break;
        }
        $this->resetPage();
    }

    public function clearAllFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->tag = '';
        $this->author = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = BlogPost::with(['user', 'category', 'tags'])
            ->where('is_published', true)
            ->orderBy('published_at', 'desc');

        if ($this->category) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->category);
            });
        }

        if ($this->tag) {
            $query->whereHas('tags', function ($q) {
                $q->where('slug', $this->tag);
            });
        }

        if ($this->author) {
            $query->where('user_id', $this->author);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('excerpt', 'like', "%{$this->search}%");
            });
        }

        $posts = $query->paginate(10);

        return view('livewire.posts.post-list', compact('posts'));
    }
}
