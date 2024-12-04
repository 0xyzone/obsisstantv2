<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\TournamentAdminResource\Pages;
use App\Filament\App\Resources\TournamentAdminResource\RelationManagers;
use App\Models\TournamentAdmin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TournamentAdminResource extends Resource
{
    protected static ?string $model = TournamentAdmin::class;

    protected static ?string $navigationIcon = 'eos-admin-panel-settings-o';
    protected static ?string $activeNavigationIcon = 'eos-admin-panel-settings';
    protected static ?string $tenantOwnershipRelationshipName = 'tournament';
    protected static ?string $tenantRelationshipName = 'admins';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ig_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ig_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('server_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tournament.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ig_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ig_id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('server_id')
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
            'index' => Pages\ListTournamentAdmins::route('/'),
            'create' => Pages\CreateTournamentAdmin::route('/create'),
            'edit' => Pages\EditTournamentAdmin::route('/{record}/edit'),
        ];
    }
}
