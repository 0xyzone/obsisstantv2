<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use App\Livewire\ApiCustomComponent;
use Filament\Navigation\NavigationItem;
use Filament\Forms\Components\FileUpload;
use Illuminate\Validation\Rules\Password;
use Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Guava\FilamentKnowledgeBase\KnowledgeBasePlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Rupadana\FilamentAnnounce\FilamentAnnouncePlugin;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Filament\Pages\Auth\EmailVerification\EmailVerificationPrompt;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('dashboard')
            ->path('dashboard')
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->emailVerification(EmailVerificationPrompt::class)
            ->sidebarFullyCollapsibleOnDesktop()
            ->databaseNotifications()
            ->brandLogo(asset('mainLogo.png'))
            ->favicon(asset('ObsistanT.png'))
            ->brandLogoHeight('6rem')
            ->discoverResources(in: app_path('Filament/Dashboard/Resources'), for: 'App\\Filament\\Dashboard\\Resources')
            ->discoverPages(in: app_path('Filament/Dashboard/Pages'), for: 'App\\Filament\\Dashboard\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Dashboard/Widgets'), for: 'App\\Filament\\Dashboard\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                \App\Http\Middleware\RedirectToStudioLogin::class,
            ])
            ->assets([
                // Js::make('obs-reconnect-script', asset('js/obsReconnect.js')),
                // Js::make('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js'),
            ])
            ->viteTheme('resources/css/filament/dashboard/theme.css')
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        navigationGroup: 'Settings', // Sets the navigation group for the My Profile page (default = null)
                        hasAvatars: true, // Enables the avatar upload form component (default = false)
                        slug: 'my-profile', // Sets the slug for the profile page (default = 'my-profile')
                    )
                    ->passwordUpdateRules(
                        rules: [Password::default()->mixedCase()->uncompromised(3)], // you may pass an array of validation rules as well. (default = ['min:8'])
                        requiresCurrentPassword: true, // when false, the user can update their password without entering their current password. (default = true)
                    )
                    ->avatarUploadComponent(fn() => FileUpload::make('avatar_url')->directory('images/profile-photos'))
                    ->enableTwoFactorAuthentication(
                        force: false, // force the user to enable 2FA before they can use the application (default = false)
                    )->enableSanctumTokens(
                        permissions: ['view'] // optional, customize the permissions (default = ["create", "view", "update", "delete"])
                    )->myProfileComponents([
                        'sanctum_tokens' => ApiCustomComponent::class,
                    ]),
                KnowledgeBasePlugin::make()
                    ->modalPreviews()
                    ->slideOverPreviews(),
                FilamentAnnouncePlugin::make()
                    ->pollingInterval('30s')
            ]);
    }
}
