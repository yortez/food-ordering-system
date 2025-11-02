<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
                    ->components([
                        TextInput::make('name')
                            ->required(),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(2),
                Section::make('Category Image')
                    ->components([
                        FileUpload::make('image')
                            ->image()
                            ->imageEditor()
                            ->directory('categories')
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }
}
