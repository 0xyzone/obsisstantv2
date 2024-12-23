<?php

namespace App\Filament\App\Pages;

use Filament\Infolists\Components\Section;
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
                'link' => 'something',
            ])
            ->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('link')
                            ->label('OBS Browser Link')
                            ->formatStateUsing(function () {
                                $full_url = "http" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . "://" . $_SERVER['HTTP_HOST'];
                                return $full_url;
                            })
                            ->copyable()
                            ->copyMessage('Copied!')
                            ->copyMessageDuration(1500),
                    ])
            ]);
    }
}
