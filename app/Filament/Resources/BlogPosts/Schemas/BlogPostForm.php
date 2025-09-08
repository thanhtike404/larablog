<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(
                                        fn(string $context, $state, callable $set) =>
                                        $context === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique('blog_posts', 'slug', ignoreRecord: true),
                            ]),

                        Textarea::make('excerpt')
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),

                        FileUpload::make('featured_image')
                            ->image()
                            ->directory('blog-images')
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Content')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                ['table', 'attachFiles'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                                ['undo', 'redo'],
                            ])
                    ]),

                Section::make('Relationships')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Author')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->default(function () {
                                        return auth()->id() ?? 1;
                                    }),

                                Select::make('category_id')
                                    ->label('Primary Category')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),

                        Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ]),

                Section::make('Publishing & SEO')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_published')
                                    ->default(false),

                                DateTimePicker::make('published_at')
                                    ->default(now()),
                            ]),

                        Textarea::make('seo_meta')
                            ->label('SEO Meta (JSON)')
                            ->placeholder('{"meta_title": "", "meta_description": "", "meta_keywords": ""}')
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('reading_time')
                                    ->numeric()
                                    ->suffix('minutes')
                                    ->disabled(),

                                TextInput::make('views_count')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled(),
                            ]),
                    ]),
            ]);
    }
}
