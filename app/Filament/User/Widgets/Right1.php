<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\Widget;

class Right1 extends Widget
{
    protected static string $view = 'filament.user.widgets.right1';
    public static function canView(): bool
    {
        return true;
    }
}
