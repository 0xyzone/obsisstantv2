<?php

namespace App\Filament\Resources\GameHeroResource\Pages;

use App\Filament\Resources\GameHeroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGameHero extends CreateRecord
{
    protected static string $resource = GameHeroResource::class;
}
