<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Tournament;
use App\Enums\TournamentType;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Forms\Components\Actions\Action;

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
                TextInput::make('name')
                    ->columnSpanFull()
                    ->required()
                    ->autofocus(),
                Select::make('game_id')
                    ->relationship('game', 'name')
                    ->default('1')
                    ->columnSpanFull()
                    ->required(),
                Select::make('type')
                    ->options(TournamentType::class)
                    ->live()
                    ->default('team')
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('max_teams')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->hidden(function (Get $get): bool {
                        if ($get('type') == null || $get('type') != 'team' && $get('type') != 'ffa') {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->columnSpanFull(),
                TextInput::make('min_players')
                    ->label('Min. Player(s) Per Team')
                    ->default(1)
                    ->numeric()
                    ->required()
                    ->hidden(function (Get $get): bool {
                        if ($get('type') == null || $get('type') != 'team' && $get('type') != 'ffa') {
                            return true;
                        } else {
                            return false;
                        }
                    }),
                TextInput::make('max_players')
                    ->label('Max. Player(s) Per Team')
                    ->default(1)
                    ->numeric()
                    ->required()
                    ->gte('min_players')
                    ->validationMessages([
                        'gte' => 'The max player should be greater than or equal to min players.'
                    ])
                    ->hidden(function (Get $get): bool {
                        if ($get('type') == null || $get('type') != 'team' && $get('type') != 'ffa') {
                            return true;
                        } else {
                            return false;
                        }
                    }),
            ])
            ->columns(2);
    }

    protected function handleRegistration(array $data): Tournament
    {
        $tournament = Tournament::create($data);

        $tournament->users()->attach(auth()->user());

        return $tournament;
    }
}
?>