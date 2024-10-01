<?php

namespace App\Filament\Dashboard\Widgets;

use Filament\Tables;
use App\Models\Tournament;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class TournamentWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        $userId = Auth::id();

        return $table
            ->query(
                Tournament::query()->whereHas('users', function ($query) use ($userId) {
                    $query->where('users.id', $userId);
                })
            )
            ->heading('Tournaments')
            ->columns([
                TextColumn::make('#')
                ->url(fn($record) => route('filament.studio.tenant.profile', $record))
                ->rowIndex(),
                ImageColumn::make('logo')
                ->url(fn($record) => route('filament.studio.tenant.profile', $record)),
                TextColumn::make('name')
                ->url(fn($record) => route('filament.studio.tenant.profile', $record)),
                TextColumn::make('teams_count')->counts('teams')
                ->label('Total Teams')
                ->alignCenter()
                ->url(fn($record) => route('filament.studio.tenant.profile', $record)),
                TextColumn::make('teams.name')
                ->label('Teams List')
                ->limitList(3)
                // ->listWithLineBreaks()
                ->badge()
                ->url(fn($record) => route('filament.studio.tenant.profile', $record)),
            ])
            ->actions([
                Tables\Actions\Action::make('Edit')
                ->url(fn($record) => route('filament.studio.tenant.profile', $record))
                ->icon('heroicon-o-pencil'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Create Tournament')
                ->url(route('filament.studio.tenant.registration'))
            ]);
    }
}
