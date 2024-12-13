<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class Versus extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
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
            ->state([
                'link' => 'link',
                'test' => 'test',
            ])
            ->add(TextEntry::make('test'))
            ->schema([
                TextEntry::make('link')
                    ->label('Test')
                    ->formatStateUsing(function () {
                        return 'Hello world';
                    })
                    ->copyable()
                    ->copyMessage('Copied!')
                    ->copyMessageDuration(1500),
                ViewEntry::make('link')
                    ->view('infolists.components.versus-link')
                    ->label('link')
            ]);
    }
}
