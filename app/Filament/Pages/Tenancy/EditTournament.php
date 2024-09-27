<?php

namespace App\Filament\Pages\Tenancy;
use Filament\Forms\Form;
use App\Enums\TournamentType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTournament extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return "Edit Tournament";
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
                            ->relationship('game', 'name'),
                        Select::make("type")
                            ->options(TournamentType::class),
                        DatePicker::make("start_date")
                            ->label("Starts From"),
                        DatePicker::make("end_date")
                            ->label("Ends On"),
                    ])
                    ->columns(2)
                    ->columnSpan(2),
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
}