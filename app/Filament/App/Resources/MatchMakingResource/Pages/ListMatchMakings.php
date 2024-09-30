<?php

namespace App\Filament\App\Resources\MatchMakingResource\Pages;

use App\Filament\App\Resources\MatchMakingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMatchMakings extends ListRecords
{
    protected static string $resource = MatchMakingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
