<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Tournament;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Facades\Filament;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use App\Livewire\ApiCustomComponent;
use Filament\Support\Enums\MaxWidth;
use App\Filament\Auth\CustomRegister;
use Illuminate\Support\Facades\Blade;
use App\Http\Middleware\AuthMiddleware;
use Filament\Navigation\NavigationItem;
use App\Http\Middleware\CheckUserTenant;
use Filament\Navigation\NavigationGroup;
use Filament\Forms\Components\FileUpload;
use Illuminate\Validation\Rules\Password;
use App\Http\Middleware\ApplyTenantScopes;
use Filament\Http\Middleware\Authenticate;
use Filament\Support\Facades\FilamentView;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Filament\Pages\Tenancy\EditTournament;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Pages\Tenancy\RegisterTournament;
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

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('studio')
            ->default()
            ->login()
            ->emailVerification(EmailVerificationPrompt::class)
            ->passwordReset()
            ->registration(CustomRegister::class)
            ->sidebarFullyCollapsibleOnDesktop()
            ->databaseNotifications()
            ->tenant(Tournament::class, ownershipRelationship: 'tournament')
            ->tenantRoutePrefix('tournament')
            ->tenantRegistration(RegisterTournament::class)
            ->tenantProfile(EditTournament::class)
            ->path('studio')
            ->brandName('Obsisstant v2')
            ->brandLogo(asset('mainLogo.png'))
            ->favicon(asset('ObsistanT.png'))
            ->brandLogoHeight('6rem')
            ->colors([
                'primary' => Color::Emerald,
            ])
            // ->unsavedChangesAlerts()
            ->maxContentWidth(MaxWidth::Full)
            ->font('Poppins')
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Assets')
                    ->icon('eos-config-map'),
            ])
            ->navigationItems([
                NavigationItem::make('Edit Tournament')
                    ->url(fn(): string => EditTournament::getUrl())
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.studio.tenant.profile'))
                    ->icon('heroicon-o-trophy')
                    ->activeIcon('heroicon-s-trophy'),
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
                ApplyTenantScopes::class,
                CheckUserTenant::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->assets([
                // Js::make('obs-reconnect-script', asset('js/obsReconnect.js')),
                Js::make('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js'),
            ])
            ->viteTheme('resources/css/filament/studio/theme.css')
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        hasAvatars: true, // Enables the avatar upload form component (default = false)
                        slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
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
                    ->pollingInterval('30s'),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ]);
    }
}
