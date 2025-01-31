<?php

namespace App\Filament\Dashboard\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Caster;
use Filament\Forms\Form;
use App\Enums\HandleType;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Dashboard\Resources\CasterResource\Pages;
use App\Filament\Dashboard\Resources\CasterResource\RelationManagers;

class CasterResource extends Resource
{
    protected static ?string $model = Caster::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth::id()),
                Forms\Components\TextInput::make('display_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('vdo_ninja_url')
                    ->required()
                    ->activeUrl()
                    ->maxLength(255),
                Forms\Components\TextInput::make('handle')
                    ->maxLength(255),
                Forms\Components\Select::make('handle_type')
                    ->options(HandleType::class),
                Forms\Components\FileUpload::make('photo_path')
                    ->required()
                    ->image()
                    ->directory('img/casters'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('user_id', auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('display_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('handle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('handle_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vdo_ninja_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('photo_path')
                    ->searchable(),
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
            'index' => Pages\ListCasters::route('/'),
            'create' => Pages\CreateCaster::route('/create'),
            'edit' => Pages\EditCaster::route('/{record}/edit'),
        ];
    }
}
