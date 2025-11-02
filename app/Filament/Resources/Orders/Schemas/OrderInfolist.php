<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order_number'),
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('customer_name')
                    ->placeholder('-'),
                TextEntry::make('customer_phone')
                    ->placeholder('-'),
                TextEntry::make('customer_email')
                    ->placeholder('-'),
                TextEntry::make('subtotal')
                    ->numeric(),
                TextEntry::make('tax_amount')
                    ->numeric(),
                TextEntry::make('discount_amount')
                    ->numeric(),
                TextEntry::make('total_amount')
                    ->numeric(),
                TextEntry::make('payment_method'),
                TextEntry::make('payment_status'),
                TextEntry::make('order_status'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('completed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
