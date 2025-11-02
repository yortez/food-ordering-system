<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->searchable()
                    ->label('Customer')
                    ->placeholder('N/A'),

                TextColumn::make('total_amount')
                    ->money('php')
                    ->sortable()
                    ->summarize(Sum::make()->money('php')),

                TextColumn::make('payment_status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'primary' => 'partially_paid',
                        'danger' => 'refunded',
                        'gray' => 'cancelled',
                    ]),

                TextColumn::make('order_status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'gray' => 'refunded',
                    ]),

                TextColumn::make('payment_method')
                    ->badge()
                    ->color('info'),

                TextColumn::make('user.name')
                    ->label('Cashier')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'partially_paid' => 'Partially Paid',
                        'refunded' => 'Refunded',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('order_status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ]),

                SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'card' => 'Card',
                        'digital_wallet' => 'Digital Wallet',
                        'bank_transfer' => 'Bank Transfer',
                    ]),

                // Filter::make('created_at')
                //     ->form([
                //         DatePicker::make('created_from'),
                //         DatePicker::make('created_until'),
                //     ])
                //     ->query(function (Builder $query, array $data): Builder {
                //         return $query
                //             ->when(
                //                 $data['created_from'],
                //                 fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                //             )
                //             ->when(
                //                 $data['created_until'],
                //                 fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                //             );
                //     })
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('print_receipt')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(fn(Order $record) => redirect()->route('pos.receipt', $record))
                    ->openUrlInNewTab(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
