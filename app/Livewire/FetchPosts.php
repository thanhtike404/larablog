<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BlogPost;
use Livewire\Attributes\On;

class FetchPosts extends Component
{
    public $posts = [];
    public $loading = false;


    // #[On('post-fetching')]
    public function fetchPosts()
    {


        $this->posts = BlogPost::all();



        // Force a re-render
        $this->dispatch('$refresh');
    }
    // #[On('test-fetch')]
    public function testFetch()
    {
        $this->posts = BlogPost::all();
    }
    public function render()
    {
        $posts = BlogPost::paginate(10);

        return view('livewire.fetch-posts', [
            'posts' => $posts,

        ]);
    }
}
