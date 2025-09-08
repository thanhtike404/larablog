<?php

namespace App\Livewire;

use App\Services\BlogService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

class BlogPostList extends Component
{
    use WithPagination;

    #[Url(as: 'search')]
    public string $search = '';

    #[Url(as: 'category')]
    public string $category = '';

    #[Url(as: 'tag')]
    public string $tag = '';

    public int $perPage = 12;

    public function __construct(
        private readonly BlogService $blogService
    ) {}

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedTag(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'category', 'tag']);
        $this->resetPage();
    }

    #[Computed]
    public function posts()
    {
        if ($this->search) {
            return $this->blogService->searchPosts($this->search, $this->perPage);
        }

        if ($this->tag) {
            return $this->blogService->getPostsByTag($this->tag, $this->perPage);
        }

        if ($this->category) {
            return $this->blogService->getPostsByCategory($this->category, $this->perPage);
        }

        return $this->blogService->getPublishedPosts($this->perPage);
    }

    public function render()
    {
        return view('livewire.blog-post-list');
    }
}
