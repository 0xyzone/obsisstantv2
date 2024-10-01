<?php

namespace App\Filament\Imports;

use App\Models\TournamentTeam;
use Filament\Facades\Filament;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;
use Exception;

class TournamentTeamImporter extends Importer
{
    protected static ?string $model = TournamentTeam::class;

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
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('short_name')
                ->rules(['max:255']),
            ImportColumn::make('email')
                ->rules(['email', 'max:255']),
            ImportColumn::make('contact_number')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('alternative_contact_number')
                ->numeric()
                ->rules(['integer']),
        ];
    }

    public function resolveRecord(): ?TournamentTeam
    {
        return TournamentTeam::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'name' => $this->data['name'],
            'short_name' => $this->data['short_name'],
            'email' => $this->data['email'],
            'tournament_id' => $this->tenant->id
        ], [
            'tournament_id' => $this->tenant->id
        ]);

        // return new TournamentTeam([
        //     'tournament_id' => $this->tenant->id,
        // ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your tournament team import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    public function getJobBatchName(): ?string
    {
        return 'teams-import';
    }
}
