<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Schema;

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
                                        $context === 'create' ? $set('slug', \Str::slug($state)) : null
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

                        RichEditor::make('content_blocks')
                            ->label('Content')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'heading',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'codeBlock',
                            ]),
                    ]),

                Section::make('Relationships')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Author')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->default(auth()->id()),

                                Select::make('category_id')
                                    ->label('Primary Category')
                                    ->relationship('category', 'name')
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required(),
                                        TextInput::make('slug')
                                            ->required(),
                                    ]),
                            ]),

                        Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('slug')
                                    ->required(),
                                TextInput::make('color')
                                    ->default('#3B82F6'),
                                Textarea::make('description'),
                                TextInput::make('official_url')
                                    ->url(),
                            ])
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

                        KeyValue::make('seo_meta')
                            ->keyLabel('Meta Key')
                            ->valueLabel('Meta Value')
                            ->default([
                                'meta_title' => '',
                                'meta_description' => '',
                                'meta_keywords' => '',
                            ])
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
