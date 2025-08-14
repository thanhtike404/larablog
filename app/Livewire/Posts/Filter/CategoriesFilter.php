<?php

namespace App\Livewire\Posts\Filter;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Redis;

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
        // Build the filter string that will be hashed
        $filterString = 'search:' . $this->currentSearch . '|tag:' . $this->currentTag . '|author:' . $this->currentAuthor;


        $cacheKey = 'categories_filter:' . md5($filterString);



        try {
            // Try to get cached data from Redis directly
            $cachedData = Redis::get($cacheKey);

            if ($cachedData) {
                // Unserialize the cached data
                $categories = unserialize($cachedData);
            } else {
                // Query the database
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

                // Cache the data in Redis for 1 hour (3600 seconds)
                Redis::setex($cacheKey, 3600, serialize($categories));
            }
        } catch (\Exception $e) {
            // Fallback to database query without caching
            $categories = Category::withCount(['posts' => function ($query) {
                $query->where('is_published', true);

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
        }

        return view('livewire.posts.filter.categories-filter', compact('categories'));
    }

    /**
     * Clear category cache when posts are updated
     */
    public static function clearCache()
    {
        try {
            // Get the Redis prefix from config
            $prefix = config('database.redis.options.prefix', '');

            // Search for keys with the Laravel prefix
            $pattern = $prefix . 'categories_filter:*';
            $keys = Redis::keys($pattern);

            if (!empty($keys)) {
                // Remove the prefix from keys before deleting (Redis facade handles this automatically)
                $keysWithoutPrefix = array_map(function ($key) use ($prefix) {
                    return str_replace($prefix, '', $key);
                }, $keys);

                Redis::del($keysWithoutPrefix);
            }
        } catch (\Exception $e) {
            // Silently handle cache clearing errors
        }
    }
}
