<?php

namespace App\Filament\App\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\GameHero;
use Filament\Forms\Form;
use App\Models\MatchStat;
use App\Models\TeamPlayer;
use Filament\Tables\Table;
use App\Models\MatchMaking;
use App\Models\TournamentTeam;
use Filament\Facades\Filament;
use App\Models\TournamentAdmin;
use Filament\Resources\Resource;
use App\Models\TournamentWebhook;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\MatchMakingResource\Pages;
use App\Filament\App\Resources\MatchMakingResource\RelationManagers;

class MatchMakingResource extends Resource
{
    protected static ?string $model = MatchMaking::class;
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Manage';
    protected static ?string $navigationLabel = 'Matches';
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
                        ->rules([
                            function (Model $record = null) {
                                return function (string $attribute, $value, Closure $fail) use ($record) {
                                    $currentRecordId = $record ? $record->id : null;
                                    $matches = Filament::getTenant()->matches;
                                    if ($matches->where('id', '!=', $currentRecordId)->contains('title', $value)) {
                                        $fail('The :attribute must be unique per tournament.');
                                    };
                                };
                            }
                        ])
                        ->required(),
                    Forms\Components\Select::make('match_winner')
                        ->visibleOn('edit')
                        ->options(function (Get $get): array {
                            return TournamentTeam::where('id', $get('team_a'))->orWhere('id', $get('team_b'))->pluck('name', 'id')->toArray();
                        })
                        ->hidden(fn(Get $get): bool => ($get('team_a') != null && $get('team_b') != null) ? false : true),
                    Forms\Components\Select::make('tournament_admin_id')
                        ->visibleOn('edit')
                        ->relationship(
                            name: 'admin',
                            titleAttribute: 'ig_name',
                            modifyQueryUsing:
                            fn(Builder $query, Get $get) => $query->whereBelongsTo(Filament::getTenant())
                        )
                        ->hidden(fn(Get $get): bool => ($get('team_a') != null && $get('team_b') != null) ? false : true),
                ])
                    ->columns(3),
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
                            fn(Builder $query, Get $get) => $query->whereBelongsTo(Filament::getTenant())->where('id', '!=', $get('team_b'))
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
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($record, $state) {
                            $record->team_a_mp = $state;
                            $record->save();
                            Notification::make()
                                ->title('Saved Successfully.')
                                ->success()
                                ->send();
                        })
                        ->visibleOn('edit'),
                    Repeater::make('statsForTeamA')
                        ->label('Players')
                        ->relationship(
                            name: 'statsForTeamA',
                            modifyQueryUsing:
                            fn(Builder $query, Get $get, $record) => $query->where('tournament_team_id', $get('team_a'))
                        )
                        ->addActionLabel('Add player')
                        ->visibleOn('edit')
                        ->defaultItems(1)
                        ->deleteAction(
                            fn(Action $action) => $action->requiresConfirmation(),
                        )
                        ->extraItemActions([
                            Action::make('update')
                                ->label('Update')
                                ->action(function (array $arguments, Repeater $component) {
                                    $itemData = $component->getItemState($arguments['item']);
                                    MatchStat::where('id', $itemData['id'])->update($itemData);
                                    Notification::make()
                                        ->title('Saved Successfully.')
                                        ->success()
                                        ->send();
                                    header("Refresh: 3");
                                })
                                ->button()
                        ])
                        ->schema([
                            Forms\Components\Hidden::make('id'),
                            Forms\Components\Hidden::make('tournament_team_id')
                                ->default(function (Get $get) {
                                    return $get('../../team_a');
                                }),
                            Group::make([
                                Select::make('team_player_id')
                                    ->options(function (Get $get): array {
                                        return TeamPlayer::where('tournament_team_id', $get('../../team_a'))->pluck('nickname', 'id')->toArray();
                                    })
                                    ->required()
                                    ->label('Player')
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->live()
                                    ->preload()
                                    ->distinct()
                                    ->searchable()
                                    ->columnSpan(3),
                                Forms\Components\Select::make('game_hero_id')
                                    ->label('Hero')
                                    ->default(null)
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->options(function (Get $get): array {
                                        $tournament = Filament::getTenant();
                                        return GameHero::where('game_id', $tournament->game->id)->pluck('name', 'id')->toArray();
                                    })
                                    ->columnSpan(3)
                                    ->searchable()
                                    ->preload()
                                    ->live(),
                                Forms\Components\Placeholder::make('Image')
                                    ->label('')
                                    ->content(function (Get $get) {
                                        $hero = GameHero::where('id', $get('game_hero_id'))->first();
                                        if ($hero && $hero->image_url) { // Check if hero and image_url exist
                                            return new HtmlString('<img class="object-cover w-16 aspect-square rounded-lg mt-2" src="' . $hero->image_url . '" alt="Hero Image" style="max-width: 100%; height: auto;">');
                                        }
                                        return '';
                                    }),
                                Toggle::make('is_mvp')
                                    ->inline(false)
                                    ->live()
                                    ->distinct()
                                    ->fixIndistinctState(),
                            ])
                                ->columns(8),
                            Group::make([
                                TextInput::make('kills')
                                    ->label('')
                                    ->prefixIcon('fas-k')
                                    ->placeholder('Kills'),
                                TextInput::make('deaths')
                                    ->label('')
                                    ->prefixIcon('fas-d')
                                    ->placeholder('Deaths'),
                                TextInput::make('assists')
                                    ->label('')
                                    ->prefixIcon('fas-a')
                                    ->placeholder('Assists'),
                                TextInput::make('net_worth')
                                    ->label('')
                                    ->prefixIcon('fas-g')
                                    ->placeholder('Net worth'),
                                TextInput::make('hero_damage')
                                    ->placeholder('Hero Damage'),
                                TextInput::make('turret_damage')
                                    ->placeholder('Turret Damage'),
                                TextInput::make('damage_taken')
                                    ->placeholder('Damage Taken'),
                                TextInput::make('fight_participation')
                                    ->label('F. Participation')
                                    ->placeholder('Fight Participation')
                                    ->helperText('Type without %'),
                            ])->columns(4)
                        ])
                        ->columnSpanFull()
                        ->itemLabel(fn(array $state): ?string => $state['team_player_id'] ? TeamPlayer::where('id', $state['team_player_id'])->first()->nickname : null)
                        ->collapsible()
                        ->collapseAllAction(
                            fn(Action $action) => $action->label('Collapse all'),
                        )
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get): array {
                            $data['tournament_team_id'] = $get('team_a');
                            $data['match_making_id'] = $get('../../record.id');
                            return $data;
                        })
                        ->maxItems(5)
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
                            fn(Builder $query, Get $get) => $query->whereBelongsTo(Filament::getTenant())->where('id', '!=', $get('team_a'))
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
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($record, $state) {
                            $record->team_b_mp = $state;
                            $record->save();
                            Notification::make()
                                ->title('Saved Successfully.')
                                ->success()
                                ->send();
                        })
                        ->visibleOn('edit'),
                    Repeater::make('statsForTeamB')
                        ->label('Players')
                        ->relationship(
                            name: 'statsForTeamB',
                            modifyQueryUsing:
                            fn(Builder $query, Get $get, $record) => $query->where('tournament_team_id', $get('team_b'))
                        )
                        ->addActionLabel('Add player')
                        ->visibleOn('edit')
                        ->defaultItems(1)
                        ->deleteAction(
                            fn(Action $action) => $action->requiresConfirmation(),
                        )

                        ->extraItemActions([
                            Action::make('update')
                                ->label('Update')
                                ->action(function (array $arguments, Repeater $component) {
                                    $itemData = $component->getItemState($arguments['item']);
                                    MatchStat::where('id', $itemData['id'])->update($itemData);
                                    Notification::make()
                                        ->title('Saved Successfully.')
                                        ->success()
                                        ->send();
                                    header("Refresh: 3");
                                })
                                ->button()
                        ])
                        ->schema([
                            Forms\Components\Hidden::make('id'),
                            Forms\Components\Hidden::make('tournament_team_id')
                                ->default(function (Get $get) {
                                    return $get('../../team_b');
                                }),
                            Group::make([
                                Select::make('team_player_id')
                                    ->options(function (Get $get): array {
                                        return TeamPlayer::where('tournament_team_id', $get('../../team_b'))->pluck('nickname', 'id')->toArray();
                                    })
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required()
                                    ->label('Player')
                                    ->live()
                                    ->distinct()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(3),
                                Forms\Components\Select::make('game_hero_id')
                                    ->label('Hero')
                                    ->default(null)
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->options(function (Get $get): array {
                                        $tournament = Filament::getTenant();
                                        return GameHero::where('game_id', $tournament->game->id)->pluck('name', 'id')->toArray();
                                    })
                                    ->columnSpan(3)
                                    ->searchable()
                                    ->preload()
                                    ->live(),
                                Forms\Components\Placeholder::make('Image')
                                    ->label('')
                                    ->content(function (Get $get) {
                                        $hero = GameHero::where('id', $get('game_hero_id'))->first();
                                        if ($hero && $hero->image_url) { // Check if hero and image_url exist
                                            return new HtmlString('<img class="object-cover w-16 aspect-square rounded-lg mt-2" src="' . $hero->image_url . '" alt="Hero Image" style="max-width: 100%; height: auto;">');
                                        }
                                        return '';
                                    }),
                                Toggle::make('is_mvp')
                                    ->inline(false)
                                    ->live()
                                    ->distinct()
                                    ->fixIndistinctState(),
                            ])
                                ->columns(8),
                            Group::make([
                                TextInput::make('kills')
                                    ->label('')
                                    ->prefixIcon('fas-k')
                                    ->placeholder('Kills'),
                                TextInput::make('deaths')
                                    ->label('')
                                    ->prefixIcon('fas-d')
                                    ->placeholder('Deaths'),
                                TextInput::make('assists')
                                    ->label('')
                                    ->prefixIcon('fas-a')
                                    ->placeholder('Assists'),
                                TextInput::make('net_worth')
                                    ->label('')
                                    ->prefixIcon('fas-g')
                                    ->placeholder('Net worth'),
                                TextInput::make('hero_damage')
                                    ->placeholder('Hero Damage'),
                                TextInput::make('turret_damage')
                                    ->placeholder('Turret Damage'),
                                TextInput::make('damage_taken')
                                    ->placeholder('Damage Taken'),
                                TextInput::make('fight_participation')
                                    ->label('F. Participation')
                                    ->placeholder('Fight Participation')
                                    ->helperText('Type without %'),
                            ])->columns(4)
                        ])
                        ->itemLabel(fn(array $state): ?string => $state['team_player_id'] ? TeamPlayer::where('id', $state['team_player_id'])->first()->nickname : null)
                        ->collapsible()
                        ->collapseAllAction(
                            fn(Action $action) => $action->label('Collapse all'),
                        )
                        ->maxItems(5)
                        ->columnSpanFull()
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get): array {
                            $data['tournament_team_id'] = $get('team_b');
                            $data['match_making_id'] = $get('../../record.id');
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
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(24)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('teamA.name')
                    ->extraAttributes(function ($record) {
                        if ($record->match_winner != null) {
                            if ($record->match_winner == $record->team_a) {
                                return ['class' => 'dark:bg-lime-700 bg-lime-300'];
                            } else {
                                return ['class' => 'dark:bg-red-700 bg-red-300'];
                            }
                        } else {
                            return [];
                        }
                    }),
                Tables\Columns\TextInputColumn::make('team_a_mp')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('teamB.name')
                    ->extraAttributes(function ($record) {
                        if ($record->match_winner != null) {
                            if ($record->match_winner == $record->team_b) {
                                return ['class' => 'dark:bg-lime-700 bg-lime-300'];
                            } else {
                                return ['class' => 'dark:bg-red-700 bg-red-300'];
                            }
                        } else {
                            return [];
                        }
                    }),
                Tables\Columns\TextInputColumn::make('team_b_mp')
                    ->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->beforeStateUpdated(function (MatchMaking $record) {
                        MatchMaking::where('id', '!=', $record->id)->where('tournament_id', Filament::getTenant()->id)->update(['is_active' => false]);
                    }),
                Tables\Columns\SelectColumn::make('match_winner')
                    ->options(function ($record): array {
                        return TournamentTeam::where('id', $record->team_a)->orWhere('id', $record->team_b)->pluck('name', 'id')->toArray();
                    })
                    ->disabled(function ($record) {
                        return ($record->team_a_mp == null || $record->team_b_mp == null) ?? true;
                    }),
                Tables\Columns\SelectColumn::make('tournament_admin_id')
                    ->options(TournamentAdmin::where('tournament_id', Filament::getTenant()->id)->pluck('ig_name', 'id')),
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
                Tables\Actions\Action::make('Publish Admin info')
                    ->button()
                    ->hidden(fn($record): bool => $record->admin ? false : true)
                    ->form([
                        Select::make('tournament.webhook')
                            ->label('Select Channel')
                            ->required()
                            ->relationship(
                                name: 'tournament.webhooks',
                                titleAttribute: 'channel_name',
                                modifyQueryUsing:
                                fn(Builder $query, Get $get) => $query->whereBelongsTo(Filament::getTenant())
                            )
                    ])
                    ->action(function (Model $record, array $data) {
                        // dd('test');
                        $webhook = TournamentWebhook::find($data['tournament']['webhook']);
                        $webhookUrl = $webhook->link;
                        // dd($webhook->link);
                        $teamA = $record->teamA->name;
                        $teamB = $record->teamB->name;
                        $admin = $record->admin->ig_name . " - " . $record->admin->ig_id . " (" . $record->admin->server_id . ")";
                        // dd([$admin, $teamA, $teamB]);
            
                        $payload = [
                            'embeds' => [
                                [
                                    'title' => 'Lobby Details for: ' . $teamA . ' vs ' . $teamB,
                                    'description' => 'Admin: ' . $admin,
                                    'color' => 0xFF5733,
                                ]
                            ]
                        ];

                        Http::post($webhookUrl, $payload);

                    })
            ])
            ->defaultSort('id', 'desc')
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
