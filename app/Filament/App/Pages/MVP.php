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

class MVP extends Page implements HasInfolists, HasForms
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    protected static ?string $navigationGroup = 'Assets';
    protected static string $view = 'filament.app.pages.m-v-p';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->state([
                'link' => route('screen.mvp', ['id' => auth()->id()]),
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
