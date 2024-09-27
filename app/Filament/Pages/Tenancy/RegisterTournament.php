<?php

namespace App\Filament\Pages\Tenancy;

use App\Enums\TournamentType;
use Filament\Forms\Form;
use App\Models\Tournament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterTournament extends RegisterTenant
{
    public static function getLabel(): string
    {
        return "Register Tournament";
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                Select::make('game_id')
                ->relationship('game', 'name')
                ->default('1'),
                Select::make('type')
                ->options(TournamentType::class)
                ->default('team')
            ]);
    }

    protected function handleRegistration(array $data): Tournament
    {
        $tournament = Tournament::create($data);

        $tournament->users()->attach(auth()->user());

        return $tournament;
    }
}
?>