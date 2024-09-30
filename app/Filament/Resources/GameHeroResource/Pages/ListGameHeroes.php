<?php

namespace App\Filament\Resources\GameHeroResource\Pages;

use App\Filament\Resources\GameHeroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGameHeroes extends ListRecords
{
    protected static string $resource = GameHeroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
