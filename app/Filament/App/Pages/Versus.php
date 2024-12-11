<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\MaxWidth;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;

class Versus extends Page
{
    protected static ?string $navigationGroup = 'Assets';

    protected static string $view = 'filament.app.pages.versus';
    protected static ?string $navigationLabel = 'Versus Screen';
    protected ?string $heading = 'Versus asset with roster';
    protected ?string $subheading = 'Check out the demo of the Versus asset! You can also copy its link to use as a browser source in OBS.';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('test')
                    ->label('Test')
                    ->formatStateUsing(function () {
                        return 'Hello world';
                    })
                    ->copyable()
                    ->copyMessage('Copied!')
                    ->copyMessageDuration(1500),
                ViewEntry::make('link')
                    ->view('filament.infolists.components.versus-link')
            ]);
    }
}
