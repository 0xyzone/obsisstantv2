<?php

namespace App\Filament\App\Resources;

use Closure;
use Filament\Forms;
use Filament\Forms\Components\Split;
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
use Filament\Tables\Enums\ActionsPosition;
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

    public static function shouldRegisterNavigation(): bool
    {
        if (Filament::getTenant()->game_id === 1) {
            return true;
        } else {
            return false;
        }
        ;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->extraAttributes([
                'x-on:click' => '$store.sidebar.close()'
            ])
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
                        ->hidden(fn(Get $get): bool => ($get('team_a') != null && $get('team_b') != null) ? false : true)
                        ->live()
                        ->afterStateUpdated(function ($record, $state) {
                            $record->match_winner = $state;
                            $record->save();
                            Notification::make()
                                ->title('Winner selected!')
                                ->body($state == $record->teamA->id ? $record->teamA->name : $record->teamB->name . ' has been selected as winner for the match.')
                                ->success()
                                ->send();
                        }),
                    // Forms\Components\Select::make('tournament_admin_id')
                    //     ->visibleOn('edit')
                    //     ->relationship(
                    //         name: 'admin',
                    //         titleAttribute: 'ig_name',
                    //         modifyQueryUsing:
                    //         fn(Builder $query, Get $get) => $query->whereBelongsTo(Filament::getTenant())
                    //     )
                    //     ->hidden(fn(Get $get): bool => ($get('team_a') != null && $get('team_b') != null) ? false : true),
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
                            fn(Builder $query, Get $get) => $query->whereBelongsTo(Filament::getTenant())->where('id', '!=', $get('team_b'))
                        )
                        ->live()
                        // ->disabledOn('edit')
                        ->required(),
                    Forms\Components\TextInput::make('team_a_mp')
                        ->label(function (Get $get) {
                            if ($get('team_a') != null) {
                                return TournamentTeam::where('id', $get('team_a'))->first()->name . '\'s Match Point';
                            }
                        })
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($record, $state) {
                            $formattedState = is_numeric($state) && $state >= 0 && $state <= 9
                                ? sprintf("%02d", (int) $state)
                                : $state;
                            $record->team_a_mp = $formattedState;
                            $record->save();
                            Notification::make()
                                ->title($record->teamA->name . '\'s match point has been updated')
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
                            fn(Action $action) => $action->requiresConfirmation()
                                ->extraAttributes([
                                    'tabindex' => '-1',
                                ]),
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
                                ->extraAttributes([
                                    'tabindex' => '-1',
                                ])
                        ])
                        ->schema([
                            Forms\Components\Hidden::make('id'),
                            Forms\Components\Hidden::make('tournament_team_id')
                                ->extraAttributes([
                                    'tabindex' => '-1',
                                ])
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
                                    ->columnSpan(3)
                                    ->extraInputAttributes([
                                        'tabindex' => '-1'
                                    ]),
                                Forms\Components\Select::make('game_hero_id')
                                    ->label('Hero')
                                    ->extraInputAttributes([
                                        'tabindex' => '-1',
                                        'x-on:keydown' => 'if (["Tab","ArrowUp","ArrowDown"].includes($event.key)) $event.preventDefault()',
                                    ])
                                    // ->searchable()
                                    ->default(null)
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->options(function (Get $get): array {
                                        $tournament = Filament::getTenant();
                                        return GameHero::where('game_id', $tournament->game->id)->pluck('name', 'id')->toArray();
                                    })
                                    ->columnSpan(3)
                                    ->live()
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->update(['game_hero_id' => $state]);
                                            $hero = GameHero::where('id', $state)->first();
                                            Notification::make('Saved')
                                                ->title('Hero Selected!')
                                                ->body('Selected ' . ($hero->name ?? '') . ' for ' . $record->player->name)
                                                ->success()
                                                ->send();
                                        }
                                    }),
                                Forms\Components\Placeholder::make('Image')
                                    ->label('')
                                    ->extraAttributes([
                                        'tabindex' => '-1',
                                    ])
                                    ->content(function (Get $get) {
                                        $hero = GameHero::where('id', $get('game_hero_id'))->first();
                                        if ($hero && $hero->image_url) { // Check if hero and image_url exist
                                            return new HtmlString('<img class="object-cover w-16 aspect-square rounded-lg mt-2" src="' . $hero->image_url . '" alt="Hero Image" style="max-width: 100%; height: auto;">');
                                        }
                                        return '';
                                    }),
                                Toggle::make('is_mvp')
                                    ->inline(false)
                                    ->extraAttributes([
                                        'tabindex' => '-1',
                                    ])
                                    ->live()
                                    ->distinct()
                                    ->fixIndistinctState()
                                    ->afterStateUpdated(function ($record) {
                                        if ($record) {
                                            $match = MatchMaking::where('id', $record->match_making_id)->firstOrFail();
                                            $statsA = MatchStat::where('match_making_id', $match->id)->where('tournament_team_id', $match->team_a)->get();

                                            foreach ($statsA as $a) {
                                                if ($a->id !== $record->id) {
                                                    $a->is_mvp = false;
                                                    $a->save();
                                                } else {
                                                    $a->is_mvp = true;
                                                    $a->save();
                                                }
                                            }
                                            Notification::make('Saved')
                                                ->title('Changed MVP of ' . $record->team->name)
                                                ->body($record->player->nickname . ' has been selected as MVP from team ' . $record->team->name)
                                                ->success()
                                                ->send();
                                        }
                                    }),
                            ])
                                ->columns(8),
                            Group::make([
                                TextInput::make('kills')
                                    ->label('')
                                    ->prefixIcon('fas-k')
                                    ->placeholder('Kills')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->kills = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('deaths')
                                    ->label('')
                                    ->prefixIcon('fas-d')
                                    ->placeholder('Deaths')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->deaths = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('assists')
                                    ->label('')
                                    ->prefixIcon('fas-a')
                                    ->placeholder('Assists')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->assists = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('net_worth')
                                    ->label('')
                                    ->prefixIcon('fas-g')
                                    ->placeholder('Net worth')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->net_worth = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('hero_damage')
                                    ->placeholder('Hero Damage')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->hero_damage = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('turret_damage')
                                    ->placeholder('Turret Damage')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->turret_damage = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('damage_taken')
                                    ->placeholder('Damage Taken')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->damage_taken = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('fight_participation')
                                    ->label('F. Participation')
                                    ->placeholder('Fight Participation')
                                    ->helperText('Type without %')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->fight_participation = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                            ])->columns(4)
                        ])
                        ->columnSpanFull()
                        ->itemLabel(fn(array $state): ?string => $state['team_player_id'] ? TeamPlayer::where('id', $state['team_player_id'])->first()->nickname : null)
                        // ->collapsible()
                        // ->collapseAllAction(
                        //     fn(Action $action) => $action->label('Collapse all'),
                        // )
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
                        // ->disabledOn('edit')
                        ->required()
                        ->extraInputAttributes([
                            'tabindex' => '-1',
                        ]),
                    Forms\Components\TextInput::make('team_b_mp')
                        ->label(function (Get $get) {
                            if ($get('team_b') != null) {
                                return TournamentTeam::where('id', $get('team_b'))->first()->name . '\'s Match Point';
                            }
                        })
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($record, $state) {
                            $formattedState = is_numeric($state) && $state >= 0 && $state <= 9
                                ? sprintf("%02d", (int) $state)
                                : $state;
                            $record->team_b_mp = $formattedState;
                            $record->save();
                            Notification::make()
                                ->title($record->teamB->name . '\'s match point has been updated')
                                ->success()
                                ->send();
                        })
                        ->visibleOn('edit')
                        ->extraInputAttributes([
                            'tabindex' => '-1',
                        ]),
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
                            fn(Action $action) => $action->requiresConfirmation()
                                ->extraAttributes([
                                    'tabindex' => '-1',
                                ]),
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
                                ->extraAttributes([
                                    'tabindex' => '-1',
                                ])
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
                                    ->preload()
                                    ->columnSpan(3)
                                    ->extraInputAttributes([
                                        'tabindex' => -1,
                                    ]),
                                Forms\Components\Select::make('game_hero_id')
                                    ->extraInputAttributes([
                                        'tabindex' => -1,
                                    ])
                                    // ->searchable()
                                    ->label('Hero')
                                    ->default(null)
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->options(function (Get $get): array {
                                        $tournament = Filament::getTenant();
                                        return GameHero::where('game_id', $tournament->game->id)->pluck('name', 'id')->toArray();
                                    })
                                    ->columnSpan(3)
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->update(['game_hero_id' => $state]);
                                            $hero = GameHero::where('id', $state)->first();
                                            Notification::make('Saved')
                                                ->title('Hero Selected!')
                                                ->body('Selected ' . ($hero->name ?? '') . ' for ' . $record->player->name)
                                                ->success()
                                                ->send();
                                        }
                                    }),
                                Forms\Components\Placeholder::make('Image')
                                    ->extraAttributes([
                                        'tabindex' => '-1',
                                    ])
                                    ->label('')
                                    ->content(function (Get $get) {
                                        $hero = GameHero::where('id', $get('game_hero_id'))->first();
                                        if ($hero && $hero->image_url) { // Check if hero and image_url exist
                                            return new HtmlString('<img class="object-cover w-16 aspect-square rounded-lg mt-2" src="' . $hero->image_url . '" alt="Hero Image" style="max-width: 100%; height: auto;">');
                                        }
                                        return '';
                                    }),
                                Toggle::make('is_mvp')
                                    ->extraAttributes([
                                        'tabindex' => '-1',
                                    ])
                                    ->inline(false)
                                    ->live()
                                    ->distinct()
                                    ->fixIndistinctState()
                                    ->afterStateUpdated(function ($record) {
                                        if ($record) {
                                            $match = MatchMaking::where('id', $record->match_making_id)->firstOrFail();
                                            $statsB = MatchStat::where('match_making_id', $match->id)->where('tournament_team_id', $match->team_b)->get();
                                            foreach ($statsB as $b) {
                                                if ($b->id !== $record->id) {
                                                    $b->is_mvp = false;
                                                    $b->save();
                                                } else {
                                                    $b->is_mvp = true;
                                                    $b->save();
                                                }
                                            }
                                            Notification::make('Saved')
                                                ->title('Changed MVP of ' . $record->team->name)
                                                ->body($record->player->nickname . ' has been selected as MVP from team ' . $record->team->name)
                                                ->success()
                                                ->send();
                                        }
                                    }),
                            ])
                                ->columns(8),
                            Group::make([
                                TextInput::make('kills')
                                    ->label('')
                                    ->prefixIcon('fas-k')
                                    ->placeholder('Kills')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->kills = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('deaths')
                                    ->label('')
                                    ->prefixIcon('fas-d')
                                    ->placeholder('Deaths')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->deaths = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('assists')
                                    ->label('')
                                    ->prefixIcon('fas-a')
                                    ->placeholder('Assists')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->assists = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('net_worth')
                                    ->label('')
                                    ->prefixIcon('fas-g')
                                    ->placeholder('Net worth')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->net_worth = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('hero_damage')
                                    ->placeholder('Hero Damage')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->hero_damage = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('turret_damage')
                                    ->placeholder('Turret Damage')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->turret_damage = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('damage_taken')
                                    ->placeholder('Damage Taken')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->damage_taken = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                                TextInput::make('fight_participation')
                                    ->label('F. Participation')
                                    ->placeholder('Fight Participation')
                                    ->helperText('Type without %')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($record, $state) {
                                        if ($record) {
                                            $record->fight_participation = $state != null ? $state : 0;
                                            $record->save();
                                        }
                                    }),
                            ])->columns(4)
                        ])
                        ->itemLabel(fn(array $state): ?string => $state['team_player_id'] ? TeamPlayer::where('id', $state['team_player_id'])->first()->nickname : null)
                        // ->collapsible()
                        // ->collapseAllAction(
                        //     fn(Action $action) => $action->label('Collapse all'),
                        // )
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
            ->recordClasses(fn(Model $record) => match ($record->is_active) {
                1 => 'bg-lime-500/20 hover:!bg-lime-500/40',
                default => null
            })
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
                Tables\Actions\EditAction::make()
                ->iconButton(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Dublicate')
                ->iconButton()
                ->icon('heroicon-m-document-duplicate')
                    ->form([
                        TextInput::make('title')
                            ->default(function ($record) {
                                return $record->title;
                            }),
                        Forms\Components\Hidden::make('user_id')
                            ->default(auth()->id()),
                        Forms\Components\Hidden::make('tournament_id')
                            ->default(Filament::getTenant()->id),
                        Forms\Components\Hidden::make('team_a')
                            ->default(fn($record) => $record->team_a)
                            ->columnSpan(1),
                        Forms\Components\Hidden::make('team_b')
                            ->default(fn($record) => $record->team_b)
                            ->columnSpan(1),
                    ])
                    ->action(function (array $data) {
                        // dd($data);
                        $match = MatchMaking::create([
                            'title' => $data['title'],
                            'tournament_id' => $data['tournament_id'],
                            'user_id' => $data['user_id'],
                            'team_a' => $data['team_a'],
                            'team_b' => $data['team_b'],
                        ]);
                        Notification::make('Created')
                            ->title('Dublicated Match!')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('Publish Admin info')
                    ->iconButton()
                    ->icon('heroicon-s-megaphone')
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
            ], position: ActionsPosition::BeforeColumns)
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
            // 'create' => Pages\CreateMatchMaking::route('/create'),
            'edit' => Pages\EditMatchMaking::route('/{record}/edit'),
        ];
    }
}
