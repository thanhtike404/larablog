<?php

namespace App\DTOs;

class BlogPostData
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
        public readonly ?string $excerpt = null,
        public readonly ?string $featuredImage = null,
        public readonly ?array $contentBlocks = null,
        public readonly ?array $seoMeta = null,
        public readonly bool $isPublished = false,
        public readonly ?int $categoryId = null,
        public readonly array $tagIds = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            content: $data['content'] ?? '',
            excerpt: $data['excerpt'] ?? null,
            featuredImage: $data['featured_image'] ?? null,
            contentBlocks: $data['content_blocks'] ?? null,
            seoMeta: $data['seo_meta'] ?? null,
            isPublished: $data['is_published'] ?? false,
            categoryId: $data['category_id'] ?? null,
            tagIds: $data['tag_ids'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'featured_image' => $this->featuredImage,
            'content_blocks' => $this->contentBlocks,
            'seo_meta' => $this->seoMeta,
            'is_published' => $this->isPublished,
            'category_id' => $this->categoryId,
            'published_at' => $this->isPublished ? now() : null,
        ];
    }
}
