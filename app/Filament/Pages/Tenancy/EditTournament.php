<?php

namespace App\Filament\Pages\Tenancy;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Actions\Action;
use App\Enums\TournamentType;
use Filament\Facades\Filament;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Support\HtmlString;

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
                        DatePicker::make("start_date")
                            ->label("")
                            ->timezone('America/Kathmandu')
                            ->native(false)
                            ->minDate(today())
                            ->displayFormat('jS M, Y')
                            ->firstDayOfWeek(7)
                            ->closeOnDateSelection()
                            ->prefix('Starts')
                            ->live(),
                        DatePicker::make("end_date")
                            ->label("")
                            ->timezone('America/Kathmandu')
                            ->native(false)
                            ->minDate(fn(Get $get) => $get('start_date') ? $get('start_date') : now())
                            ->displayFormat('jS M, Y')
                            ->firstDayOfWeek(7)
                            ->closeOnDateSelection()
                            ->prefix('Ends'),
                        TextInput::make('max_teams')
                            ->numeric()
                            ->required()
                            ->live()
                            ->columnSpanFull()
                            ->hidden(function (Get $get): bool {
                                if ($get('type') == null || $get('type') != 'team' && $get('type') != 'ffa') {
                                    return true;
                                } else {
                                    return false;
                                }
                            }),
                        TextInput::make('min_players')
                            ->numeric()
                            ->required()
                            ->live()
                            ->hidden(function (Get $get): bool {
                                if ($get('type') == null || $get('type') != 'team' && $get('type') != 'ffa') {
                                    return true;
                                } else {
                                    return false;
                                }
                            }),
                        TextInput::make('max_players')
                            ->numeric()
                            ->required()
                            ->gte('min_players')
                            ->hidden(function (Get $get): bool {
                                if ($get('min_players') == null || $get('type') != 'team' && $get('type') != 'ffa') {
                                    return true;
                                } else {
                                    return false;
                                }
                            }),
                    ])
                    ->columns(2)
                    ->columnSpan([
                        'md'=> '2',
                    ]),
                Section::make("Theme")
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
                    ->columnSpan(1),
                Section::make([
                    FileUpload::make("logo")
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
                    ->columnSpan(1)
            ])
            ->columns(4);
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