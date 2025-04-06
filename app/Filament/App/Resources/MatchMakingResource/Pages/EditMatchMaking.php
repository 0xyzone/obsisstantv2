<?php

namespace App\Filament\App\Resources\MatchMakingResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Get;
use App\Filament\App\Resources\MatchMakingResource;

class EditMatchMaking extends EditRecord
{
    protected static string $resource = MatchMakingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            // Actions\Action::make('update')
            // ->label('Update')
            // ->action(function () {
            //     $data = $this->form->getState();
            //     $formFields['title'] = $data['title'];
            //     $formFields['match_winner'] = $data['match_winner'];
            //     $formFields['tournament_admin_id'] = $data['tournament_admin_id'];
            //     $this->record->update($formFields);
            //     Notification::make()
            //             ->title('Saved Successfully.')
            //             ->success()
            //             ->send();
            // })
        ];
    }
    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()
            ->disabled();
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
