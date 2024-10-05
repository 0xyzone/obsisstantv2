<?php

namespace App\Livewire;

use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Laravel\Sanctum\PersonalAccessToken;
use Filament\Tables\Concerns\InteractsWithTable;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class ApiCustomComponent extends MyProfileComponent implements HasTable
{
    use InteractsWithTable;
    public static $sort = 20;
    protected string $view = "livewire.api-custom-component";

    public function mount()
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
        $this->userClass = get_class($this->user);

    }

    public function getTable(): Table
    {
        return Table::make($this)
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('created_at')->label('Created At')->date()->sortable(),
                TextColumn::make('token')
                    ->label('Token')
                    ->formatStateUsing(fn($state) => $this->maskSensitiveData($state))
                    ->copyable()->copyMessage('Token Copied!')->copyMessageDuration(3000)
                    ->tooltip('Click to copy the token!'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->headerActions([])
            ->actions([]); // No row actions as per your requirement
    }

    protected function maskSensitiveData(string $data, int $visibleLength = 4): string
    {
        $length = strlen($data);

        // If the string is shorter than or equal to the visible length, return it as is.
        if ($length <= $visibleLength) {
            return $data;
        }

        // Maximum number of asterisks to show
        $maxAsterisks = 5;

        // Calculate how many asterisks to display
        $maskedLength = min($length - $visibleLength, $maxAsterisks);

        // Create the masked string with asterisks and the visible part
        $maskedData = str_repeat('*', $maskedLength) . substr($data, -$visibleLength);

        return $maskedData;
    }

    public function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return PersonalAccessToken::query()
            ->where('tokenable_id', $this->user->id)
            ->where('tokenable_type', get_class($this->user)); // Ensure you have a `tokens` relationship in your User model
    }

    public function table(): Table
    {
        return $this->getTable(); // Call your existing getTable method
    }
}
