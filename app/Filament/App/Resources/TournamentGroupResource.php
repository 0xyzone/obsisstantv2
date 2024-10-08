<?php

namespace App\Filament\App\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use App\Models\TournamentGroup;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\TournamentGroupResource\Pages;
use App\Filament\App\Resources\TournamentGroupResource\RelationManagers;

class TournamentGroupResource extends Resource
{
    protected static ?string $model = TournamentGroup::class;

    protected static ?string $navigationGroup = 'Manage';
    protected static ?string $navigationIcon = 'fluentui-people-team-24';
    protected static ?string $activeNavigationIcon = 'fluentui-people-team-24-o';
    protected static ?string $tenantOwnershipRelationshipName = 'tournament';
    protected static ?string $tenantRelationshipName = 'groups';
    protected static ?int $navigationSort = 5;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->rules([ 
                        function (Model $record = null) {
                            return function (string $attribute, $value, Closure $fail) use ($record) {
                                $currentRecordId = $record ? $record->id : null;
                                $matches = Filament::getTenant()->groups;
                                if ($matches->where('id', '!=', $currentRecordId)->contains('name', $value)) {
                                    $fail('Group :attribute must be unique per tournament.');
                                };
                            };
                        }
                    ])
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->inline(false),
                Repeater::make('groupTeams')
                    ->relationship('groupTeams')
                    ->hiddenOn('create')
                    ->schema([
                        Hidden::make('tournament_id')
                            ->default(fn() => Filament::getTenant()->id),
                        Forms\Components\Select::make('tournament_team_id')
                            ->required()
                            ->relationship(
                                name: 'team',
                                titleAttribute: 'name',
                                modifyQueryUsing:
                                fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant())
                            ),
                        Forms\Components\TextInput::make('w'),
                        Forms\Components\TextInput::make('d'),
                        Forms\Components\TextInput::make('l'),
                        Forms\Components\TextInput::make('f'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('tournament.logo')
                ->label('Tournament Logo')
                    ->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->beforeStateUpdated(function (TournamentGroup $record) {
                        TournamentGroup::where('id', '!=', $record->id)->where('tournament_id', Filament::getTenant()->id)->update(['is_active' => false]);
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
            'index' => Pages\ListTournamentGroups::route('/'),
            'create' => Pages\CreateTournamentGroup::route('/create'),
            'edit' => Pages\EditTournamentGroup::route('/{record}/edit'),
        ];
    }
}
