<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class Pos extends Page
{
    protected string $view = 'filament.pages.pos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
}
