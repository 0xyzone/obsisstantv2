<?php

namespace App\Filament\Imports;

use App\Models\GameHero;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class GameHeroImporter extends Importer
{
    protected static ?string $model = GameHero::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('game')
                ->relationship(resolveUsing: 'name'),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): ?GameHero
    {
        // return GameHero::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new GameHero();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your game hero import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
