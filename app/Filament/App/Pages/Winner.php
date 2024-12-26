<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class Winner extends Page implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Assets';
    protected static string $view = 'filament.app.pages.winner';
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->state([
                'link' => route('screen.winner', ['id' => auth()->id()]),
            ])
            ->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('link')
                            ->label('OBS Browser Link')
                            ->copyable()
                            ->copyMessage('Copied!')
                            ->copyMessageDuration(1500),
                    ])
            ]);
    }
}
