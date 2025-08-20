<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BlogPostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('title'),
                IconEntry::make('is_published')
                    ->boolean(),
                TextEntry::make('published_at')
                    ->dateTime(),
                TextEntry::make('slug'),
                TextEntry::make('user.name'),
                TextEntry::make('category.name'),
                ImageEntry::make('featured_image'),
                TextEntry::make('reading_time')
                    ->numeric(),
                TextEntry::make('views_count')
                    ->numeric(),
            ]);
    }
}
