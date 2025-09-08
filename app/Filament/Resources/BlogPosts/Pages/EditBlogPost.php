<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Set published_at if the post is being published for the first time
        if ($data['is_published'] && empty($this->record->published_at)) {
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

    protected function afterSave(): void
    {
        // Recalculate reading time after saving
        $this->record->calculateReadingTime();
        $this->record->save();
    }
}
