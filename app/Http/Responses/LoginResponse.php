<?php

namespace App\Http\Responses;

use App\Filament\Resources\OrderResource;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        if (Filament::getCurrentPanel()->getId() === 'event') {
            return redirect()->to('/');
        }

        // Here, you can define which resource and which page you want to redirect to
        return parent::toResponse($request);
    }
}
