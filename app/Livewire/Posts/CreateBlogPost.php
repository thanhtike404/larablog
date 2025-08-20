<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Models\BlogPost;

use Livewire\WithFileUploads;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CreateBlogPost extends Component
{

    use WithFileUploads;

    // Form fields
    public $title = '';
    public $content = '';
    public $main_image;
    public $image;
    public $is_published = false;
    public $published_at = '';
    public $slug = '';
    public $category_id = '';
    public $selectedTags = [];
    public $tagSearch;
    // Content blocks for rich editor
    public $contentBlocks = [];
    public $blockCounter = 0;

    // Available options
    public $categories = [];
    public $tags = [];

    // UI states
    public $showPreview = false;
    public $currentEditingBlock = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'main_image' => 'nullable|image|max:2048',
        'image' => 'nullable|image|max:1024',
        'category_id' => 'required|exists:categories,id',
        'selectedTags' => 'array',
        'selectedTags.*' => 'exists:tags,id',
        'slug' => 'required|string|unique:blog_posts,slug',
    ];

    protected $messages = [
        'title.required' => 'The title field is required.',
        'content.required' => 'The content field is required.',
        'category_id.required' => 'Please select a category.',
        'slug.unique' => 'This slug is already taken.',
    ];

    public function mount()
    {
        $this->categories = Category::all();
        $this->tags = Tag::all();
        $this->published_at = now()->format('Y-m-d\TH:i');

        // Initialize with a default paragraph block
        $this->addContentBlock('paragraph');
    }

    public function getTagsForJsProperty()
    {
        return $this->tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name
            ];
        });
    }

    public function updatedTitle()
    {
        $this->slug = Str::slug($this->title);
    }

    public function addContentBlock($type = 'paragraph')
    {
        $blockId = 'block_' . ++$this->blockCounter;

        $defaultData = [
            'paragraph' => ['text' => ''],
            'heading' => ['text' => '', 'level' => 2],
            'code' => ['code' => '', 'language' => 'php'],
            'list' => ['items' => [''], 'style' => 'unordered'],
            'quote' => ['text' => '', 'caption' => ''],
            'image' => ['url' => '', 'caption' => '', 'alt' => ''],
        ];

        $this->contentBlocks[] = [
            'id' => $blockId,
            'type' => $type,
            'data' => $defaultData[$type] ?? ['text' => ''],
            'order' => count($this->contentBlocks) + 1
        ];
    }

    public function removeContentBlock($index)
    {
        unset($this->contentBlocks[$index]);
        $this->contentBlocks = array_values($this->contentBlocks);

        // Reorder blocks
        foreach ($this->contentBlocks as $key => $block) {
            $this->contentBlocks[$key]['order'] = $key + 1;
        }
    }

    public function moveBlockUp($index)
    {
        if ($index > 0) {
            $temp = $this->contentBlocks[$index];
            $this->contentBlocks[$index] = $this->contentBlocks[$index - 1];
            $this->contentBlocks[$index - 1] = $temp;

            // Update order
            $this->contentBlocks[$index]['order'] = $index + 1;
            $this->contentBlocks[$index - 1]['order'] = $index;
        }
    }

    public function moveBlockDown($index)
    {
        if ($index < count($this->contentBlocks) - 1) {
            $temp = $this->contentBlocks[$index];
            $this->contentBlocks[$index] = $this->contentBlocks[$index + 1];
            $this->contentBlocks[$index + 1] = $temp;

            // Update order
            $this->contentBlocks[$index]['order'] = $index + 1;
            $this->contentBlocks[$index + 1]['order'] = $index + 2;
        }
    }

    public function addListItem($blockIndex)
    {
        $this->contentBlocks[$blockIndex]['data']['items'][] = '';
    }

    public function removeListItem($blockIndex, $itemIndex)
    {
        unset($this->contentBlocks[$blockIndex]['data']['items'][$itemIndex]);
        $this->contentBlocks[$blockIndex]['data']['items'] = array_values($this->contentBlocks[$blockIndex]['data']['items']);
    }

    public function togglePreview()
    {
        $this->showPreview = !$this->showPreview;
    }

    public function save($publish = false)
    {
        $this->is_published = $publish;

        if ($publish && empty($this->published_at)) {
            $this->published_at = now()->format('Y-m-d\TH:i');
        }

        $this->validate();

        // Handle file uploads
        $mainImagePath = null;
        $imagePath = null;

        if ($this->main_image) {
            $mainImagePath = $this->main_image->store('blog/main-images', 'public');
        }

        if ($this->image) {
            $imagePath = $this->image->store('blog/featured-images', 'public');
        }

        // Prepare content blocks JSON
        $contentBlocksJson = json_encode([
            'blocks' => $this->contentBlocks
        ]);

        // Create blog post
        $blogPost = BlogPost::create([
            'title' => $this->title,
            'content' => $contentBlocksJson, // Store blocks as content
            'main_image' => $mainImagePath,
            'image' => $imagePath,
            'is_published' => $this->is_published,
            'published_at' => $this->is_published ? $this->published_at : null,
            'slug' => $this->slug,
            'user_id' => Auth::id(),
            'category_id' => $this->category_id,
        ]);

        // Attach tags
        if (!empty($this->selectedTags)) {
            $blogPost->tags()->attach($this->selectedTags);
        }

        session()->flash('success', $publish ? 'Blog post published successfully!' : 'Blog post saved as draft!');

        return redirect()->route('admin.blog.index');
    }
    public function filterTags()
    {
        // This method can be used for server-side filtering if needed
        // For now, we're handling filtering on the client-side with Alpine.js
        $this->dispatch('tagsFiltered');
    }
    public function saveDraft()
    {
        $this->save(false);
    }

    public function publish()
    {
        $this->save(true);
    }

    public function render()
    {
        $tagsForJs = $this->tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name
            ];
        });

        return view('livewire.posts.create-blog-post', [
            'tagsForJs' => $tagsForJs
        ]);
    }
}
