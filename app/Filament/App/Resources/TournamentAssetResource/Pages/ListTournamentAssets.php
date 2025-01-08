<?php

namespace App\Filament\App\Resources\TournamentAssetResource\Pages;

use Filament\Actions;
use Filament\Facades\Filament;
use App\Models\TournamentAsset;
use Filament\Resources\Pages\ListRecords;
use App\Filament\App\Resources\TournamentAssetResource;

class ListTournamentAssets extends ListRecords
{
    protected static string $resource = TournamentAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->hidden(function (): bool {
                $tournament = Filament::getTenant()->id;
                $asset = TournamentAsset::where('tournament_id', $tournament)->get();
                if($asset->count() < 1) {
                    return false;
                } else {
                    return true;
                }
            }),
        ];
    }
}
