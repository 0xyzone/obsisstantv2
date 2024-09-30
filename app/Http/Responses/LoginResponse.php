<?php
namespace App\Http\Responses;

use App\Filament\Dashboard\Pages\ObsSetting;
use Filament\Pages\Dashboard;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        if($request->user()->name === "Super Admin") {
            return redirect()->route("filament.admin.pages.dashboard");
        }
        return redirect()->to(route('filament.dashboard.pages.dashboard'));
    }
}