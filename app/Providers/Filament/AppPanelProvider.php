<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Tournament;
use Filament\PanelProvider;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Blade;
use Filament\Navigation\NavigationItem;
use App\Http\Middleware\CheckUserTenant;
use App\Http\Middleware\ApplyTenantScopes;
use Filament\Http\Middleware\Authenticate;
use Filament\Support\Facades\FilamentView;
use App\Filament\Pages\Tenancy\EditTournament;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Pages\Tenancy\RegisterTournament;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('studio')
            ->default()
            ->login()
            ->registration()
            ->tenant(Tournament::class, ownershipRelationship: 'tournament')
            ->tenantRoutePrefix('tournament')
            ->tenantRegistration(RegisterTournament::class)
            ->tenantProfile(EditTournament::class)
            ->path('studio')
            ->brandName('Obsisstant v2')
            ->brandLogo(asset('mainLogo.png'))
            ->brandLogoHeight('6rem')
            ->favicon(asset('ObsistanT.png'))
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->unsavedChangesAlerts()
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
            ->navigationItems([
                NavigationItem::make('Edit Tournament')
                ->url(fn(): string => EditTournament::getUrl())
                ->icon('heroicon-o-trophy')
                ->sort(-2),
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
            ]);
    }
}
