<?php

namespace App\Filament\Pages\Tenancy;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Actions\Action;
use App\Enums\TournamentType;
use Filament\Facades\Filament;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Forms\Components\Actions\Action as FormAction;

class EditTournament extends EditTenantProfile
{
    public static function getLabel(): string
    {
        
        return "Edit Tournament";
        // return new HtmlString('<em>Test</em>');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Basic Info")
                    ->headerActions([
                        FormAction::make('save')
                            ->label('Save')
                            ->action('save')
                            ->button()
                    ])
                    ->collapsible()
                    ->schema([
                        TextInput::make("name")
                            ->required()
                            ->columnSpanFull(),
                        Select::make('game_id')
                            ->relationship('game', 'name')
                            ->disabledOn('edit'),
                        Select::make("type")
                            ->options(TournamentType::class)
                            ->disabledOn('edit'),
                        Section::make('Duration/Time Span')
                            ->schema([
                                DatePicker::make("start_date")
                                    ->label("")
                                    ->timezone('Asia/Kathmandu')
                                    ->native(false)
                                    ->minDate(today())
                                    ->displayFormat('jS M, Y')
                                    ->firstDayOfWeek(7)
                                    ->closeOnDateSelection()
                                    ->prefix('Starts')
                                    ->live(),
                                DatePicker::make("end_date")
                                    ->label("")
                                    ->timezone('Asia/Kathmandu')
                                    ->native(false)
                                    ->minDate(fn(Get $get) => $get('start_date') ? $get('start_date') : now())
                                    ->displayFormat('jS M, Y')
                                    ->firstDayOfWeek(7)
                                    ->closeOnDateSelection()
                                    ->prefix('Ends'),
                            ])->columns(2),
                        Group::make([
                            Section::make('Teams')
                                ->schema([
                                    TextInput::make('max_teams')
                                        ->label('Max teams')
                                        ->placeholder('Max teams')
                                        ->numeric()
                                        ->required()
                                        ->default(1)
                                        ->hidden(function (Get $get): bool {
                                            if ($get('type') == null || $get('type') != 'team' && $get('type') != 'ffa') {
                                                return true;
                                            } else {
                                                return false;
                                            }
                                        }),
                                ])
                                ->columnSpan(1),
                            Section::make('Players')
                                ->schema([
                                    TextInput::make('min_players')
                                        ->label('Min. Player(s) Per Team')
                                        ->default(1)
                                        ->numeric()
                                        ->required()
                                        ->hidden(function (Get $get): bool {
                                            if ($get('type') == null || $get('type') != 'team' && $get('type') != 'ffa') {
                                                return true;
                                            } else {
                                                return false;
                                            }
                                        }),
                                    TextInput::make('max_players')
                                        ->label('Max. Player(s) Per Team')
                                        ->placeholder('Max. Player(s) Per Team')
                                        ->default(1)
                                        ->numeric()
                                        ->required()
                                        ->gte('min_players')
                                        ->validationMessages([
                                            'gte' => 'The max player should be greater than or equal to min players.'
                                        ])
                                        ->hidden(function (Get $get): bool {
                                            if ($get('type') == null || $get('type') != 'team' && $get('type') != 'ffa') {
                                                return true;
                                            } else {
                                                return false;
                                            }
                                        }),
                                ])->columns(2)->columnSpan(1)

                        ])->columns(2)
                    ])
                    ->columnSpan([
                        'md' => '2',
                    ]),
                Group::make([
                    Section::make([
                        Section::make('Logo')
                            ->headerActions([
                                FormAction::make('save')
                                    ->label('Save')
                                    ->action('save')
                                    ->button()
                            ])
                            ->schema([
                                FileUpload::make("logo")
                                    ->label('')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '1:1',
                                    ])
                                    ->panelAspectRatio("1:1")
                                    ->panelLayout('integrated')
                                    ->openable()
                                    ->downloadable()
                                    ->moveFiles()
                                    ->directory('images/tournaments/logo'),
                            ])
                            ->collapsible(),
                        Section::make("Theme")
                            ->headerActions([
                                FormAction::make('save')
                                    ->label('Save')
                                    ->action('save')
                                    ->button()
                            ])
                            ->schema([
                                ColorPicker::make("primary_color")
                                    ->rgba()
                                    ->required(),
                                ColorPicker::make("secondary_color")
                                    ->rgba()
                                    ->required(),
                                ColorPicker::make("accent_color")
                                    ->rgba(),
                            ])
                            ->collapsible()
                            ->columnSpan(1),
                    ])
                ])
                    ->columnSpan(1)
            ])
            ->columns(3);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Create New Tournament')
                ->url(route('filament.studio.tenant.registration'))
                ->label('Create New Tournament'),
        ];
    }
}