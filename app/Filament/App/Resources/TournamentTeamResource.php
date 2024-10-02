<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
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
                        ->required(),
                    Forms\Components\TextInput::make('short_name'),
                ])
                    ->columnSpan(3)
                    ->heading('Basic Details'),
                Section::make([
                    Forms\Components\TextInput::make('email')
                        ->email(),
                    Forms\Components\Split::make([
                        Forms\Components\TextInput::make('contact_number')
                            ->tel(),
                        Forms\Components\TextInput::make('alternative_contact_number')
                            ->label('Alt. Number')
                            ->different('contact_number')
                            ->tel(),
                    ]),
                ])
                    ->columnSpan([
                        'default' => '3',
                        'md' => '2'
                    ])
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
                        ->columnSpan([
                            'default' => '3'
                        ])
                        ->openable()
                        ->downloadable()
                        ->moveFiles()
                        ->directory('images/teams/logo'),
                ])
                    ->columnSpan([
                        'default' => '3',
                        'md' => '1'
                    ]),
                Repeater::make('players')
                    ->relationship()
                    ->columnSpan([
                        'default' => '3',
                        'md' => 'full'
                    ])
                    ->columns(16)
                    ->schema([
                        Forms\Components\Hidden::make('tournament_id')
                            ->default(Filament::getTenant()->id),
                        Forms\Components\Toggle::make('is_playing')
                            ->label('Playing')
                            ->inline(false)
                            ->grow(false)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('nickname')
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('ingame_id')
                            ->columnSpan(3),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ])
                            ->default('male')
                            ->columnSpan(3),
                        Forms\Components\FileUpload::make('photo')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->columnSpan(3)
                            ->panelLayout('integrated')
                            ->openable()
                            ->downloadable()
                            ->moveFiles()
                            ->directory('images/teams/players'),
                    ])
                    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                        $data['tournament_id'] = Filament::getTenant()->id;
                        return $data;
                    })
                    ->maxItems(fn() => Filament::getTenant()->max_players ?? PHP_INT_MAX)
                    ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
            ])
            ->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    Split::make([
                        ImageColumn::make('logo')
                            ->grow(false)
                            ->size(100)
                            ->hidden(function ($state) {
                                return $state ? false : true;
                            })
                            ->visibleFrom('md'),
                        Stack::make([
                            Stack::make([
                                TextColumn::make('name')
                                    ->size(TextColumnSize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->icon('heroicon-o-users')
                                    ->iconColor('primary')
                                    ->searchable(isIndividual: true)
                                    ->suffix(fn($record) => " (" . $record->short_name . ")"),
                                TextColumn::make('created_at')
                                    ->since()
                                    ->size(TextColumnSize::ExtraSmall)
                                    ->color('gray')
                                    ->prefix('Created '),
                            ]),
                            Panel::make([
                                Stack::make([
                                    TextColumn::make('email')
                                        ->searchable(isIndividual: true)
                                        ->iconColor('primary')
                                        ->icon('heroicon-o-envelope'),
                                    Split::make([
                                        TextColumn::make('contact_number')
                                            ->searchable(isIndividual: true)
                                            ->iconColor('primary')
                                            ->icon('heroicon-m-device-phone-mobile'),
                                        TextColumn::make('alternative_contact_number')
                                            ->searchable(isIndividual: true)
                                            ->iconColor('primary')
                                            ->icon('heroicon-s-device-phone-mobile'),
                                    ])
                                ])->space(2)
                            ])
                        ])
                            ->space(3),
                    ]),
                    Panel::make([
                        TextColumn::make('players.name')
                            ->badge()
                    ]),
                ])
                    ->space(3),
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
            'index' => Pages\ListTournamentTeams::route('/'),
            'create' => Pages\CreateTournamentTeam::route('/create'),
            'edit' => Pages\EditTournamentTeam::route('/{record}/edit'),
        ];
    }
}
