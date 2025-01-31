<?php

namespace App\Filament\Dashboard\Resources\CasterResource\Pages;

use App\Filament\Dashboard\Resources\CasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCasters extends ListRecords
{
    protected static string $resource = CasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
