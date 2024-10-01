<?php

namespace App\Filament\Resources\GameHeroResource\Pages;

use App\Filament\Imports\GameHeroImporter;
use App\Filament\Resources\GameHeroResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListGameHeroes extends ListRecords
{
    protected static string $resource = GameHeroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make('heroes')
            ->importer(GameHeroImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
