<?php
namespace App\Http\Responses;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
  public function toResponse($request): RedirectResponse|Redirector
  {
    // You can use the Filament facade to get the current panel and check the ID




      return redirect(Filament::getPanel('user')->getPath());


    return parent::toResponse($request);
  }
}
