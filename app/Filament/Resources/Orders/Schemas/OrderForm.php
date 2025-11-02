<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Schemas\Components\Section as ComponentsSection;

class OrderForm
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsSection::make('Order Information')
                    ->schema([
                        TextInput::make('order_number')
                            ->disabled()
                            ->default(fn() => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8)) . '-' . time()),

                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->default(auth()->id()),

                        TextInput::make('customer_name')
                            ->maxLength(255),

                        TextInput::make('customer_phone')
                            ->maxLength(20),

                        TextInput::make('customer_email')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                ComponentsSection::make('Payment Details')
                    ->schema([
                        Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'digital_wallet' => 'Digital Wallet',
                                'bank_transfer' => 'Bank Transfer',
                            ])
                            ->required()
                            ->default('cash'),

                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'partially_paid' => 'Partially Paid',
                                'refunded' => 'Refunded',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('paid'),

                        Select::make('order_status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->default('completed'),
                    ])
                    ->columns(3),

                ComponentsSection::make('Financial Details')
                    ->schema([
                        TextInput::make('subtotal')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        TextInput::make('tax_amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        TextInput::make('discount_amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),

                        TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                    ])
                    ->columns(4),

                ComponentsSection::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        DateTimePicker::make('completed_at')
                            ->default(now()),
                    ])
                    ->columns(2),
            ]);
    }
}
