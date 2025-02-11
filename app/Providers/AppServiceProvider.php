<?php

namespace App\Providers;

use App\Models\Setting;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\View\View;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;


class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
      $this->app->singleton(
        LoginResponse::class,
        \App\Http\Responses\LoginResponse::class
      );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Pdf::default()

            ->withBrowsershot(function (Browsershot $shot) {
                $shot->noSandbox()
                    ->setChromePath(Setting::first()->exePath);
            })
            ->margins(10, 10, 20, 10, );

        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
        $panelSwitch
          ->canSwitchPanels(fn (): bool => Auth::user()->is_admin==1)
          ->visible(fn (): bool => Auth::user()->is_admin==1)
          ->slideOver();

      });
      FilamentColor::register([
        'Fuchsia' =>  Color::Fuchsia,
        'green' =>  Color::Green,
        'blue' =>  Color::Blue,
        'gray' =>  Color::Gray,
        'yellow' =>  Color::Yellow,
        'rose' => Color::Rose,
      ]);

      FilamentView::registerRenderHook(
        'panels::page.end',
        fn (): View => view('analytics'),
        scopes: [
          \App\Filament\Resources\VictimResource::class,
        ]
      );
      FilamentAsset::register([
        \Filament\Support\Assets\Js::make('example-external-script', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js'),

      ]);
      LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en','fr']); // also accepts a closure
        });
      Model::unguard();

    }
}
