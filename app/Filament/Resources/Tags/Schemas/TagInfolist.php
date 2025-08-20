<?php

namespace App\Filament\Resources\Tags\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TagInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('color'),
                TextEntry::make('official_url'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
