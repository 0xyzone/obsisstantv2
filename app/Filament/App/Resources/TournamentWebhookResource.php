<?php

namespace App\Filament\App\Resources;

use Exception;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\TournamentWebhook;
use Illuminate\Support\Facades\Http;
use Filament\Navigation\NavigationItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\TournamentWebhookResource\Pages;
use App\Filament\App\Resources\TournamentWebhookResource\RelationManagers;

class TournamentWebhookResource extends Resource
{
    protected static ?string $model = TournamentWebhook::class;
    protected static ?string $navigationGroup = 'Manage';

    protected static ?string $navigationIcon = 'eos-webhook';
    protected static ?string $tenantOwnershipRelationshipName = 'tournament';
    protected static ?string $tenantRelationshipName = 'webhooks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('link')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        preg_match('/webhooks\/([^\/]+)\/([^\/]+)/', $state, $matches);
                        if (count($matches) !== 3) {
                            throw new Exception("Invalid webhook URL.");
                        }
                        [$fullMatch, $webhookId, $webhookToken] = $matches;
                        // Fetch webhook details
                        $webhookResponse = Http::get("https://discord.com/api/webhooks/{$webhookId}/{$webhookToken}");

                        if ($webhookResponse->failed()) {
                            throw new Exception("Failed to fetch webhook details.");
                        }

                        $webhookData = $webhookResponse->json();
                        // dd($webhookData);
                        // Get channel ID from webhook details
                        $channelId = $webhookData['channel_id'] ?? null;

                        if ($channelId) {
                            // Step 2: Fetch channel details using the bot token
                            $botToken = env('BOT_TOKKEN'); // Replace with your actual bot token
                            $channelResponse = Http::withHeaders([
                                'Authorization' => "Bot {$botToken}",
                            ])->get("https://discord.com/api/channels/{$channelId}");

                            if ($channelResponse->successful()) {
                                $channelData = $channelResponse->json();
                                $channelName = $channelData['name'] ?? 'Unknown';
                                $set('channel_name', $channelName);
                            } else {
                                throw new Exception("Failed to fetch channel details.");
                            }
                        } else {
                            throw new Exception("Channel ID not found in webhook details.");
                        }
                    })
                    ->maxLength(255),
                Forms\Components\TextInput::make('channel_name')
                    ->required()
                    ->readOnly()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('S. No.')
                    ->rowIndex()
                    ->alignRight(),
                Tables\Columns\TextColumn::make('tournament.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('channel_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('link')
                    ->limit(20),
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
                Tables\Actions\EditAction::make(),
            ])
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
            'index' => Pages\ListTournamentWebhooks::route('/'),
            'create' => Pages\CreateTournamentWebhook::route('/create'),
            'edit' => Pages\EditTournamentWebhook::route('/{record}/edit'),
        ];
    }
}
