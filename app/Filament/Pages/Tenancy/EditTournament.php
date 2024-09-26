<?php

namespace App\Filament\Pages\Tenancy;
use App\Enums\TournamentType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTournament extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return "Edit Tournament";
    }

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make("name")
            ->columnSpanFull(),
            Select::make('game_id')
            ->relationship('game', 'name'),
            Select::make("type")
            ->options(TournamentType::class),
        ])
        ->columns(3);
    }
}