<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\TournamentAssetResource\Pages;
use App\Filament\App\Resources\TournamentAssetResource\RelationManagers;
use App\Models\TournamentAsset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TournamentAssetResource extends Resource
{
    protected static ?string $model = TournamentAsset::class;

    protected static ?string $navigationGroup = 'Manage';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'tournament';
    protected static ?string $tenantRelationshipName = 'assets';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('tournament_overview')
                ->image()
                ->directory('tournament.assets.overview'),
                Forms\Components\FileUpload::make('bracket')
                ->image()
                ->directory('tournament.assets.bracket'),
                Forms\Components\FileUpload::make('schedule')
                ->image()
                ->directory('tournament.assets.schedule'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('tournament.name'),
                Tables\Columns\ImageColumn::make('tournament_overview'),
                Tables\Columns\ImageColumn::make('bracket'),
                Tables\Columns\ImageColumn::make('schedule'),
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
            'index' => Pages\ListTournamentAssets::route('/'),
            'create' => Pages\CreateTournamentAsset::route('/create'),
            'edit' => Pages\EditTournamentAsset::route('/{record}/edit'),
        ];
    }
}
