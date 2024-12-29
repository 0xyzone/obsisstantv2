<?php

namespace App\Filament\Imports;

use App\Models\TeamPlayer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TeamPlayerImporter extends Importer
{
    protected static ?string $model = TeamPlayer::class;

    public $tenant = null;

    public function __construct(
        protected Import $import,
        protected array $columnMap,
        protected array $options,
    ) {
        $this->tenant = filament()->getTenant();
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('team')
                ->relationship(resolveUsing: function ($value) {
                    return $this->tenant->teams()->where('name', $value)->first();
                }),
            ImportColumn::make('name')
                ->rules(['max:255']),
            ImportColumn::make('nickname')
                ->rules(['max:255']),
            ImportColumn::make('ingame_id'),
            ImportColumn::make('gender'),
        ];
    }

    public function resolveRecord(): ?TeamPlayer
    {
        return TeamPlayer::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'ingame_id' => $this->data['ingame_id'],
            'tournament_id' => $this->tenant->id
        ]);

        // return new TeamPlayer([
        //     'tournament_id' => $this->tenant->id,
        // ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your team player import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    public function getJobBatchName(): ?string
    {
        return 'players-import';
    }
}
