<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class StartScreen extends Page
{
    protected static ?string $navigationGroup = 'Screen';
    protected static ?int $navigationSort = 99;
    protected static ?string $navigationIcon = 'heroicon-o-tv';
    protected static ?string $activeNavigationIcon = 'heroicon-m-tv';

    protected static string $view = 'filament.app.pages.start-screen';
}
