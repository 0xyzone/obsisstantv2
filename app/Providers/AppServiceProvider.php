<?php

namespace App\Providers;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use App\Http\Responses\LogoutResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Guava\FilamentKnowledgeBase\Filament\Panels\KnowledgeBasePanel;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
        $this->app->singleton(
            LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
        KnowledgeBasePanel::configureUsing(
            fn(KnowledgeBasePanel $panel) => $panel
                ->viteTheme('resources/css/filament/studio/theme.css') // your filament vite theme path here
                ->brandName('Obsisstant Sahayatri')
                ->colors([
                    'primary' => Color::Emerald,
                ])
                ->disableBreadcrumbs()
                ->guestAccess()
                ->favicon(asset('ObsistanT.png'))
                ->middleware([
                    \App\Http\Middleware\ReplaceMarkdownPlaceholders::class,
                ])
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Gate::policy(\Spatie\Permission\Models\Role::class, \App\Policies\RolePolicy::class);

        Http::macro('withoutSsl', function () {
            return Http::withOptions(['verify' => false]);
        });

        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch
                ->modalHeading('Available Panels')
                ->simple()
                ->visible(function () {
                    $panelAdmin = Filament::getPanel('admin');
                    $panelStudio = Filament::getPanel('studio');
                    $panelDashboard = Filament::getPanel('dashboard');

                    if (Filament::getCurrentPanel()->getId() === $panelAdmin->getId()) {
                        return false;
                    } elseif (Filament::getCurrentPanel()->getId() === $panelStudio->getId()) {
                        return true;
                    } elseif (Filament::getCurrentPanel()->getId() === $panelDashboard->getId()) {
                        return false;  // Same for studio and dashboard
                    }
                })
                ->excludes([
                    'admin',
                    'knowledge-base',
                    'studio'
                ]);
        });
    }
}
