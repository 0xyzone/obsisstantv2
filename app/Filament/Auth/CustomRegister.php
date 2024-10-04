<?php

namespace App\Filament\Auth;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\Rules\Password;
use AbanoubNassem\FilamentGRecaptchaField\Forms\Components\GRecaptcha;

class CustomRegister extends Register
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->label('Name')
                ->autofocus(),
            TextInput::make('email')
                ->email()
                ->required()
                ->label('Email'),
            TextInput::make('password')
                ->password()
                ->required()
                ->revealable(filament()->arePasswordsRevealable())
                ->rule(Password::default())
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->same('passwordConfirmation')
                ->label('Password')
                ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute')),
            TextInput::make('passwordConfirmation')
                ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->required()
                ->dehydrated(false),
            GRecaptcha::make('captcha')
                ->label('Captcha')
                ->required(),
        ]);
    }
}
