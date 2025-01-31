<?php

namespace App\Filament\Dashboard\Resources\CasterResource\Pages;

use App\Filament\Dashboard\Resources\CasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCaster extends EditRecord
{
    protected static string $resource = CasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
