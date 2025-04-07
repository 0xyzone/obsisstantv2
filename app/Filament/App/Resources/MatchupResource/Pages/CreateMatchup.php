<?php

namespace App\Filament\App\Resources\MatchupResource\Pages;

use App\Filament\App\Resources\MatchupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMatchup extends CreateRecord
{
    protected static string $resource = MatchupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
