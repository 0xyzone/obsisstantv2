<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Matchup;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TournamentTeam;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\MatchupResource\Pages;
use App\Filament\App\Resources\MatchupResource\RelationManagers;

class MatchupResource extends Resource
{
    protected static ?string $model = Matchup::class;

    protected static ?string $navigationGroup = 'Manage';
    protected static ?string $navigationIcon = 'heroicon-m-calendar';
    protected static ?string $activeNavigationIcon = 'heroicon-m-calendar-days';
    protected static ?string $tenantOwnershipRelationshipName = 'tournament';
    protected static ?string $tenantRelationshipName = 'matcheups';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('schedule')
                    ->native(false)
                    ->seconds(false)
                    ->hourMode(12)
                    ->displayFormat('j F, Y h:i A')
                    ->firstDayOfWeek(7)
                    ->closeOnDateSelection(),
                    // ->minDate(today()),
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
                        fn(Builder $query, Get $get) => $query->whereBelongsTo(Filament::getTenant())->where('id', '!=', $get('team_b'))
                    )
                    ->reactive()
                    ->required(),
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
                        fn(Builder $query, Get $get) => $query->whereBelongsTo(Filament::getTenant())->where('id', '!=', $get('team_a'))
                    )
                    ->reactive()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('schedule')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teamA.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teamB.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                ->beforeStateUpdated(function (Matchup $record) {
                    Matchup::where('id', '!=', $record->id)->where('tournament_id', Filament::getTenant()->id)->update(['is_active' => false]);
                }),
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
            'index' => Pages\ListMatchups::route('/'),
            'create' => Pages\CreateMatchup::route('/create'),
            'edit' => Pages\EditMatchup::route('/{record}/edit'),
        ];
    }
}
