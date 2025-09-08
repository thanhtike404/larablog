<?php

namespace App\Listeners;

use App\Events\BlogPostPublished;
use App\Services\BlogCacheService;

class ClearBlogCache
{
    public function __construct(
        private readonly BlogCacheService $cacheService
    ) {}

    public function handle(BlogPostPublished $event): void
    {
        $this->cacheService->clearPostCache($event->post);
    }
}
