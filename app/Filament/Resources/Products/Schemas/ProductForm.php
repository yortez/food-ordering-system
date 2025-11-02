<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Details')
                    ->components([
                        TextInput::make('name')
                            ->required(),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(2),
                Group::make()
                    ->components([
                        Section::make('Price Details')
                            ->components([
                                TextInput::make('cost')
                                    ->required()
                                    ->default(0)
                                    ->prefix('₱')
                                    ->readOnly(),
                                TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('₱')
                                    ->live(true)
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        // You can add logic here if price changes
                                    }),
                            ]),
                        Section::make('Image')
                            ->components([
                                FileUpload::make('image')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorMode(1),
                            ]),
                    ])->columnSpan(1),
                Section::make('Ingredients')
                    ->components([
                        Repeater::make('productRecipes')
                            ->relationship()
                            ->schema([
                                Select::make('ingredient_id')
                                    ->relationship('ingredient', 'name')
                                    ->required()
                                    ->live(true)
                                    ->afterStateUpdated(function (Set $set, $get, $state) {
                                        // When ingredient changes, recalculate cost
                                        $quantity = $get('quantity') ?? 0;
                                        $ingredientPrice = $state ? \App\Models\Ingredient::find($state)?->price ?? 0 : 0;
                                        $set('item_cost', $ingredientPrice * $quantity);
                                    }),
                                TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->live(true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::updateTotalCost($get, $set);
                                    })

                            ])
                            ->live(true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotalCost($get, $set);
                            })
                    ])
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }
    public static function updateTotalCost(Get $get, Set $set): void
    {
        $selectedIngredients = collect($get('productRecipes'))->filter(fn($item) => !empty($item['ingredient_id']) && !empty($item['quantity']));

        $subtotal = $selectedIngredients->reduce(function ($subtotal, $ingredient) {
            // Fetch the ingredient from database to get its price
            $ingredientModel = \App\Models\Ingredient::find($ingredient['ingredient_id']);
            if ($ingredientModel && $ingredientModel->price) {
                return $subtotal + ($ingredientModel->price * $ingredient['quantity']);
            }
            return $subtotal;
        }, 0);

        $set('cost', number_format($subtotal, 2, '.', ''));
    }
}
