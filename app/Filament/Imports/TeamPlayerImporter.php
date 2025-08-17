<?php

namespace App\Filament\Imports;

use App\Models\TeamPlayer;
use App\Models\TournamentTeam;
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
                ->requiredMapping(),
            ImportColumn::make('name')
                ->rules(['max:255']),
            ImportColumn::make('nickname')
                ->rules(['max:255']),
            ImportColumn::make('ingame_id'),
            ImportColumn::make('gender'),
        ];
    }

    protected function resolveTeamForTenant(string $teamName, int $tenantId): ?TournamentTeam
    {
        $team = TournamentTeam::where('tournament_id', $tenantId)
            ->where('name', $teamName)
            ->first();

        if (!$team) {
            // If the team does not exist, create it
            $team = TournamentTeam::create([
                'name' => $teamName,
                'tournament_id' => $tenantId,
            ]);
        }

        return $team;
    }

    public function resolveRecord(): ?TeamPlayer
    {
        // Step 1: Resolve or create the team within the current tenant (tournament)
        $team = $this->resolveTeamForTenant($this->data['team'], $this->tenant->id);
        // Step 2: Resolve or create the player within the current tenant (tournament)
        $player = TeamPlayer::where('tournament_id', $this->tenant->id)
            ->where('name', $this->data['name'])
            ->where('tournament_team_id', $team->id) // Use ingame_id to identify players uniquely within the tournament
            ->first();

        if (!$player) {
            // If no player exists, create a new one
            $player = new TeamPlayer([
                'tournament_id' => $this->tenant->id,
                'tournament_team_id' => $team->id,            // Associate with the resolved team
            ]);
        }

        // Update player details and associate with the resolved team
        $player->fill([
                'name' => $this->data['name'],       // Update player name
                'nickname' => $this->data['nickname'], // Update player nickname
                'gender' => $this->data['gender'],     // Update player gender
                'ingame_id' => $this->data['ingame_id'], // Ensure ingame_id is set   
        ]);

        return $player;

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
