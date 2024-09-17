<?php

namespace App\Filament\User\Pages;

use App\Filament\Widgets\GuestsWidget;
use Filament\Pages\Page;

class Guests extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.guests';

    protected ?string $heading='';
    protected static ?string $navigationLabel='ضيوف';
    protected static ?int $navigationSort=6;

    protected function getFooterWidgets(): array
    {
        return [
            GuestsWidget::class,
        ];
    }

}
