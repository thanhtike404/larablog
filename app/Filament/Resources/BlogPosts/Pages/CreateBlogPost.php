<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPost extends CreateRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set the author to the current user if not set
        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->id() ?? 1; // Fallback to user ID 1 if not authenticated
        }

        // Set published_at if the post is being published
        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // Convert content to content_blocks format if content exists
        if (!empty($data['content']) && empty($data['content_blocks'])) {
            $data['content_blocks'] = [
                'blocks' => [
                    [
                        'type' => 'paragraph',
                        'data' => ['text' => $data['content']],
                        'order' => 1
                    ]
                ]
            ];
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Calculate reading time after creation
        $this->record->calculateReadingTime();
        $this->record->save();
        dd($this->record);
    }
}
