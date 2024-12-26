<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

class MVPH2H extends Page
{

    protected static ?string $navigationGroup = 'Assets';
    protected static string $view = 'filament.app.pages.m-v-p-h2-h';
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->state([
                'link' => route('screen.mvph2h', ['id' => auth()->id()]),
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
