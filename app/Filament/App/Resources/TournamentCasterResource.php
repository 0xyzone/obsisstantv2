<?php

namespace App\Filament\App\Resources;

use App\Enums\Position;
use App\Filament\App\Resources\TournamentCasterResource\Pages;
use App\Filament\App\Resources\TournamentCasterResource\RelationManagers;
use App\Models\TournamentCaster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TournamentCasterResource extends Resource
{
    protected static ?string $model = TournamentCaster::class;

    protected static ?string $navigationGroup = 'Manage';
    protected static ?string $navigationIcon = 'eva-mic-outline';
    protected static ?string $activeNavigationIcon = 'eva-mic';
    protected static ?string $tenantOwnershipRelationshipName = 'tournament';
    protected static ?string $tenantRelationshipName = 'casters';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('caster_id')
                    ->relationship(
                        name: 'caster',
                        titleAttribute: 'display_name',
                        modifyQueryUsing:
                        fn(Builder $query) => $query->where('user_id', auth()->id())
                    )
                    ->required(),
                Forms\Components\Select::make('position')
                    ->options(Position::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('caster.display_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('caster.photo_path'),
                Tables\Columns\TextColumn::make('caster.vdo_ninja_url'),
                Tables\Columns\SelectColumn::make('position')
                ->options([
                    "1" => "One",
                    "2" => "Two",
                    "3" => "Three",
                ]),
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
            'index' => Pages\ListTournamentCasters::route('/'),
            'create' => Pages\CreateTournamentCaster::route('/create'),
            'edit' => Pages\EditTournamentCaster::route('/{record}/edit'),
        ];
    }
}
