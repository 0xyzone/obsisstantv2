<?php

namespace App\Filament\App\Resources\MatchupResource\Pages;

use App\Filament\App\Resources\MatchupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMatchups extends ListRecords
{
    protected static string $resource = MatchupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
