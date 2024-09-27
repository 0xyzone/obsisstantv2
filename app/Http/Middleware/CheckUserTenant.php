<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Panel;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Symfony\Component\HttpFoundation\Response;

class CheckUserTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $panel = Filament::getCurrentPanel();
        $excludedRoutes = [
            'filament.studio.tenant.registration', // The tenant registration route
        ];
        if($user && $user->getTenants($panel)->isEmpty() && !in_array($request->route()->getName(), $excludedRoutes)){
            return redirect()->route('filament.dashboard.pages.dashboard');
        }
        return $next($request);
    }
}
