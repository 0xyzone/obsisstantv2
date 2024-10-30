<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;

class LogoutResponse implements Responsable
{
    public function toResponse($request): RedirectResponse
    {
        $panelAdmin = Filament::getPanel('admin');
        $panelStudio = Filament::getPanel('studio');
        $panelDashboard = Filament::getPanel('dashboard');

        if (Filament::getCurrentPanel()->getId() === $panelAdmin->getId()) {
            return redirect()->route('filament.studio.auth.login');
        } elseif (Filament::getCurrentPanel()->getId() === $panelStudio->getId()) {
            return redirect()->route('filament.studio.auth.login');
        } elseif (Filament::getCurrentPanel()->getId() === $panelDashboard->getId()) {
            return redirect()->route('filament.studio.auth.login');  // Same for studio and dashboard
        }

        return redirect()->route('filament.studio.auth.login');
    }
}