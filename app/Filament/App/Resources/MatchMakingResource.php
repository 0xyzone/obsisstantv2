<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\MatchStat;
use App\Models\TeamPlayer;
use Filament\Tables\Table;
use App\Models\MatchMaking;
use App\Models\TournamentTeam;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\MatchMakingResource\Pages;
use App\Filament\App\Resources\MatchMakingResource\RelationManagers;

class MatchMakingResource extends Resource
{
    protected static ?string $model = MatchMaking::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Manage Matches';
    protected static ?string $navigationIcon = 'tabler-tournament';
    protected static ?string $tenantOwnershipRelationshipName = 'tournament';
    protected static ?string $tenantRelationshipName = 'matches';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                ->default(auth()->id()),
                Section::make([
                    Forms\Components\TextInput::make('title')
                        ->placeholder('Eg. Match 1 Game 1')
                        ->hint('ⓘ Insert Match Title')
                        ->required(),
                    Forms\Components\Select::make('match_winner')
                        ->visibleOn('edit')
                        ->options(function (Get $get): array {
                            return TournamentTeam::where('id', $get('team_a'))->orWhere('id', $get('team_b'))->pluck('name', 'id')->toArray();
                        })
                        ->hidden(fn(Get $get): bool => ($get('team_a') != null && $get('team_b') != null) ? false : true),
                ])
                    ->columns(2),
                Section::make([
                    Forms\Components\Select::make('team_a')
                        ->label(function ($state) {
                            if ($state) {
                                return TournamentTeam::where('id', $state)->first()->name;
                            }
                        })
                        ->relationship(
                            name: 'teamA',
                            titleAttribute: 'name',
                            modifyQueryUsing:
                            fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant())
                        )
                        ->live()
                        ->disabledOn('edit')
                        ->required(),
                    Forms\Components\TextInput::make('team_a_mp')
                        ->label(function (Get $get) {
                            if ($get('team_a') != null) {
                                return TournamentTeam::where('id', $get('team_a'))->first()->name . '\'s Match Point';
                            }
                        })
                        ->visibleOn('edit')
                        ->numeric(),
                    Repeater::make('statsForTeamA')
                        ->relationship(
                            name: 'statsForTeamA',
                            modifyQueryUsing:
                            fn(Builder $query, Get $get) => $query->where('tournament_team_id', $get('team_a'))
                        )
                        ->visibleOn('edit')
                        ->defaultItems(1)
                        ->schema([
                            Forms\Components\Hidden::make('game_hero_id')
                                ->dehydrated(),
                            Forms\Components\Hidden::make('tournament_team_id')
                                ->default(function (Get $get) {
                                    return $get('../../team_a');
                                }),
                            Group::make([
                                Select::make('team_player_id')
                                    ->options(function (Get $get): array {
                                        return TeamPlayer::where('tournament_team_id', $get('../../team_a'))->pluck('name', 'id')->toArray();
                                    })
                                    ->live()
                                    ->preload()
                                    ->distinct()
                                    ->searchable()
                                    ->columnSpan(7),
                                Toggle::make('is_mvp')
                                    ->inline(false)
                                    ->live()
                                    ->distinct(),
                            ])
                                ->columns(8),
                            Group::make([
                                TextInput::make('kills'),
                                TextInput::make('deaths'),
                                TextInput::make('assists'),
                                TextInput::make('net_worth'),
                                TextInput::make('hero_damage'),
                                TextInput::make('turret_damage'),
                                TextInput::make('damage_taken'),
                                TextInput::make('fight_participation'),
                            ])->columns(4)
                        ])
                        ->columnSpanFull()
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get): array {
                            $data['tournament_team_id'] = $get('team_a');
                            $data['game_hero_id'] = 2;
                            return $data;
                        })
                ])
                    ->columns(2)
                    ->columnSpan(1),
                Section::make([
                    Forms\Components\Select::make('team_b')
                        ->label(function ($state) {
                            if ($state) {
                                return TournamentTeam::where('id', $state)->first()->name;
                            }
                        })
                        ->relationship(
                            name: 'teamB',
                            titleAttribute: 'name',
                            modifyQueryUsing:
                            fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant())
                        )
                        ->live()
                        ->disabledOn('edit')
                        ->required(),
                    Forms\Components\TextInput::make('team_b_mp')
                        ->label(function (Get $get) {
                            if ($get('team_b') != null) {
                                return TournamentTeam::where('id', $get('team_b'))->first()->name . '\'s Match Point';
                            }
                        })
                        ->visibleOn('edit')
                        ->numeric(),
                    Repeater::make('statsForTeamB')
                        ->relationship(
                            name: 'statsForTeamB',
                            modifyQueryUsing:
                            fn(Builder $query, Get $get) => $query->where('tournament_team_id', $get('team_b'))
                        )
                        ->visibleOn('edit')
                        ->defaultItems(1)
                        ->schema([
                            Forms\Components\Hidden::make('game_hero_id')
                                ->dehydrated(),
                            Forms\Components\Hidden::make('tournament_team_id')
                                ->default(function (Get $get) {
                                    return $get('../../team_b');
                                }),
                            Group::make([
                                Select::make('team_player_id')
                                    ->options(function (Get $get): array {
                                        return TeamPlayer::where('tournament_team_id', $get('../../team_b'))->pluck('name', 'id')->toArray();
                                    })
                                    ->live()
                                    ->distinct()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(7),
                                Toggle::make('is_mvp')
                                    ->inline(false)
                                    ->live()
                                    ->distinct(),
                            ])
                                ->columns(8),
                            Group::make([
                                TextInput::make('kills'),
                                TextInput::make('deaths'),
                                TextInput::make('assists'),
                                TextInput::make('net_worth'),
                                TextInput::make('hero_damage'),
                                TextInput::make('turret_damage'),
                                TextInput::make('damage_taken'),
                                TextInput::make('fight_participation'),
                            ])->columns(4)
                        ])
                        ->columnSpanFull()
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get): array {
                            $data['tournament_team_id'] = $get('team_b');
                            $data['game_hero_id'] = 2;
                            return $data;
                        })
                ])
                    ->columns(2)
                    ->columnSpan(1),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tournament.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team_a')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team_b')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('match_winner')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team_a_mp')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team_b_mp')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMatchMakings::route('/'),
            'create' => Pages\CreateMatchMaking::route('/create'),
            'edit' => Pages\EditMatchMaking::route('/{record}/edit'),
        ];
    }
}
