<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Table;
use App\Models\TournamentTeam;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\TournamentTeamResource\Pages;
use App\Filament\App\Resources\TournamentTeamResource\RelationManagers;

class TournamentTeamResource extends Resource
{
    protected static ?string $model = TournamentTeam::class;
    protected static ?string $navigationLabel = 'Teams';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $activeNavigationIcon = 'heroicon-m-users';
    protected static ?string $tenantOwnershipRelationshipName = null;
    protected static ?string $tenantRelationshipName = 'teams';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('short_name')
                        ->maxLength(255),
                ])
                    ->columnSpan(3)
                    ->heading('Basic Detaaails'),
                Section::make([
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->maxLength(255),
                    Forms\Components\Split::make([
                        Forms\Components\TextInput::make('contact_number')
                            ->numeric(),
                        Forms\Components\TextInput::make('alternative_contact_number')
                            ->numeric(),
                    ]),
                ])
                    ->columnSpan(2)
                    ->heading('Contact Details'),
                Section::make([
                    Forms\Components\FileUpload::make('logo')
                        ->label('Logo')
                        ->image()
                        ->imageEditor()
                        ->panelAspectRatio("1:1")
                        ->panelLayout('integrated')
                        ->imageEditorAspectRatios([
                            '1:1',
                        ])
                        ->openable()
                        ->downloadable()
                        ->moveFiles()
                        ->directory('images/teams/logo'),
                ])
                    ->columnSpan(1),
                Repeater::make('players')
                    ->relationship()
                    ->columnSpanFull()
                    ->grid(5)
                    ->schema([
                        Forms\Components\Hidden::make('tournament_id')
                            ->default(Filament::getTenant()->id),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nickname')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('ingame_id')
                            ->maxLength(255),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ])
                            ->default('male'),
                        Forms\Components\FileUpload::make('photo')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->panelLayout('integrated')
                            ->openable()
                            ->downloadable()
                            ->moveFiles()
                            ->directory('images/teams/players'),
                        Forms\Components\Split::make([
                            Forms\Components\Toggle::make('is_playing')
                                ->label('Playing')
                                ->default(true),
                            Forms\Components\Toggle::make('is_mvp'),
                        ])
                    ])
                    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                        $data['tournament_id'] = Filament::getTenant()->id;
                        return $data;
                    })
                    ->defaultItems(1)
                    ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
            ])
            ->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo'),
                TextColumn::make('name')
                    ->weight(FontWeight::Bold)
                    ->searchable(isIndividual: true),
                TextColumn::make('short_name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(isIndividual: true),
                TextColumn::make('contact_number')
                    ->numeric()
                    ->searchable(isIndividual: true),
                TextColumn::make('alternative_contact_number')
                    ->numeric()
                    ->searchable(isIndividual: true),
                Split::make([
                    TextColumn::make('created_at')
                        ->dateTime()
                        ->toggleable(isToggledHiddenByDefault: true),
                ]),
                Panel::make([
                    TextColumn::make('players.name')
                        ->badge()
                ])
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListTournamentTeams::route('/'),
            'create' => Pages\CreateTournamentTeam::route('/create'),
            'edit' => Pages\EditTournamentTeam::route('/{record}/edit'),
        ];
    }
}
