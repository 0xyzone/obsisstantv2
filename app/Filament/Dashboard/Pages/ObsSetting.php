<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\ObsSetting as Obs;
use Illuminate\Support\Facades\Hash;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Crypt;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class ObsSetting extends Page implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.dashboard.pages.obs-setting';

    public function mount(): void
    {
        if (auth()->user()->obsSetting) {
            $attributes = auth()->user()->obsSetting->attributesToArray();
            $attributes['password'] = Crypt::decryptString(auth()->user()->obsSetting->password);
        } else {
            $attributes = [];
        }
        $this->form->fill($attributes);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('host')
                    ->required(),
                TextInput::make('port')
                    ->numeric()
                    ->autocomplete(false)
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(),
            ])
            ->statePath('data')
            ->columns(3);
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $data['user_id'] = auth()->user()->id;
            $data['password'] = Crypt::encryptString($data['password']);
            if (auth()->user()->obsSetting == null) {
                Obs::create($data);
            } else {
                auth()->user()->obsSetting->update($data);
            }
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }
}
