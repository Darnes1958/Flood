<?php

namespace App\Providers;

use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      FilamentColor::register([
        'Fuchsia' =>  Color::Fuchsia,
        'green' =>  Color::Green,
        'blue' =>  Color::Blue,
        'gray' =>  Color::Gray,
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
