<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Login;
use App\Filament\Widgets\AreaWidget;
use App\Filament\Widgets\BigFamWidget;
use App\Filament\Widgets\Buildingwidget;
use App\Filament\Widgets\ChartCategorie;
use App\Filament\Widgets\ChartEastWest;
use App\Filament\Widgets\ChartNation;
use App\Filament\Widgets\ChartParent;
use App\Filament\Widgets\ChartRoad;
use App\Filament\Widgets\ChartYear;
use App\Filament\Widgets\ContryWidget;
use App\Filament\Widgets\FamilyShowWidget;
use App\Filament\Widgets\FamWidget;
use App\Filament\Widgets\GuestsWidget;
use App\Filament\Widgets\Left1;
use App\Filament\Widgets\MaleFemale;
use App\Filament\Widgets\Right1;
use App\Filament\Widgets\Roadwidget;
use App\Filament\Widgets\SaveWidget;
use App\Filament\Widgets\StreetWidget;
use App\Filament\Widgets\TarkebaWidget;
use App\Filament\Widgets\TriWidget;
use App\Filament\Widgets\WorkWidget;
use App\Filament\Widgets\YearWidget;
use App\Models\BigFamily;
use App\Models\Familyshow;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
          ->viteTheme('resources/css/filament/user/theme.css')
          ->brandName('فيضان درنه')
          ->profile(EditProfile::class)

          ->sidebarFullyCollapsibleOnDesktop()
          ->breadcrumbs(false)
          ->maxContentWidth('Full')
            ->id('user')
            ->path('')
            ->colors([
                'primary' => Color::Amber,
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,

                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
          ->login(Login::class)
            ->discoverResources(in: app_path('Filament/User/Resources'), for: 'App\\Filament\\User\\Resources')
            ->discoverPages(in: app_path('Filament/User/Pages'), for: 'App\\Filament\\User\\Pages')
            ->pages([

            ])
            ->discoverWidgets(in: app_path('Filament/User/Widgets'), for: 'App\\Filament\\User\\Widgets')
            ->widgets([
                \App\Filament\User\Widgets\Left1::class,
                \App\Filament\User\Widgets\Right1::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
