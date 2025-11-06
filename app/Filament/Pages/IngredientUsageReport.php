<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Order;
use App\Models\Dish;
use App\Models\Ingredient;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;

use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Schemas\Components\Form as ComponentsForm;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use BackedEnum;
use Filament\Support\Icons\Heroicon;


class IngredientUsageReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    protected string $view = 'filament.pages.ingredient-usage-report';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;



    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }


    public static function configure(Schema $schema)
    {
        return $schema
            ->components([
                ComponentsSection::make('Filter Options')
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('Date From')
                            ->live()
                            ->placeholder('Select start date'),

                        DatePicker::make('date_to')
                            ->label('Date To')
                            ->live()
                            ->placeholder('Select end date'),

                        Select::make('dish_id')
                            ->label('Dish')
                            ->options(Product::pluck('name', 'id'))
                            ->live()
                            ->placeholder('All Dishes')
                            ->searchable(),
                    ])->columns(3)
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Products Used as Recipe Ingredients')
            ->description('This report shows all products used in dish recipes across orders with their quantities.')
            ->deferLoading()
            ->query(function (): Builder {
                $dateFrom = $this->data['date_from'] ?? null;
                $dateTo = $this->data['date_to'] ?? null;
                $productId = $this->data['product_id'] ?? null;

                // Use Product model as base for Eloquent builder
                $query = Ingredient::query()
                    ->select(
                        'ingredients.id as id', // Changed from product_id to id for proper record key
                        'ingredients.name as ingredient_name',
                        'ingredients.description as ingredient_description',
                        DB::raw('SUM(product_recipes.quantity * order_items.quantity) as total_quantity_used'),
                        DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                        DB::raw('COUNT(DISTINCT products.id) as product_count')
                    )
                    ->join('product_recipes', 'ingredients.id', '=', 'product_recipes.ingredient_id')
                    ->join('products', 'product_recipes.product_id', '=', 'products.id')
                    ->join('order_items', 'products.id', '=', 'order_items.product_id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->groupBy('ingredients.id', 'ingredients.name')
                    ->orderBy('total_quantity_used', 'desc');

                // Apply date filters
                if ($dateFrom) {
                    $query->where('orders.created_at', '>=', $dateFrom);
                }

                if ($dateTo) {
                    $query->where('orders.created_at', '<=', $dateTo . ' 23:59:59');
                }

                // Apply dish filter
                if ($productId) {
                    $query->where('products.id', $productId);
                }

                return $query;
            })
            ->columns([
                TextColumn::make('ingredient_name')
                    ->label('Ingredient Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_quantity_used')
                    ->label(' Quantity ')
                    ->numeric()
                    ->sortable()
                    ->summarize(Sum::make('total_quantity_used')->label('Total Quantity')),
                TextColumn::make('ingredient_description')
                    ->label('Unit')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('order_count')
                    ->label('Number of Orders')
                    ->numeric()
                    ->sortable(),



                TextColumn::make('product_count')
                    ->label('Number of Products')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([])
            ->paginated(false);
    }

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Action::make('print')
    //             ->label('Print Report')
    //             ->icon('heroicon-o-printer')
    //             ->action('printReport'),

    //         ExportAction::make()
    //             ->label('Export to Excel')
    //             ->icon('heroicon-o-arrow-down-tray')
    //             ->exports([
    //                 ExcelExport::make()
    //                     ->fromTable()
    //                     ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
    //                     ->withFilename('order-recipe-report-' . date('Y-m-d'))
    //             ]),
    //     ];
    // }

    // public function printReport()
    // {
    //     $formData = $this->form->getState();
    //     $dateFrom = $formData['date_from'] ?? null;
    //     $dateTo = $formData['date_to'] ?? null;
    //     $dishId = $formData['dish_id'] ?? null;

    //     $reportData = $this->getFilteredTableQuery()->get();

    //     $dishName = $dishId ? Product::find($dishId)->name : 'All Dishes';

    //     $printHtml = view('print.order-recipe-report', [
    //         'reportData' => $reportData,
    //         'dateFrom' => $dateFrom,
    //         'dateTo' => $dateTo,
    //         'dishName' => $dishName,
    //     ])->render();

    //     $this->dispatch('print-report-html', ['html' => $printHtml]);
    // }
}
