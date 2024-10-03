<?php

namespace App\Filament\App\Resources\MatchMakingResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use TomatoPHP\FilamentApi\Traits\InteractWithAPI;
use App\Filament\App\Resources\MatchMakingResource;

class ListMatchMakings extends ListRecords
{
    protected static string $resource = MatchMakingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create Match'),
        ];
    }
}
