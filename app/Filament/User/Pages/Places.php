<?php

namespace App\Filament\User\Pages;

use App\Livewire\AreaWidget;
use App\Livewire\Buildingwidget;
use App\Livewire\Roadwidget;
use App\Livewire\StreetWidget;
use Filament\Pages\Page;

class Places extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.places';
    protected ?string $heading='';
    protected static ?string $navigationLabel='العناوين';
    protected static ?int $navigationSort=5;
    public function getFooterWidgetsColumns(): int | string | array
    {
        return 4;
    }

    protected function getFooterWidgets(): array
    {
        return [
            AreaWidget::make(),
            Roadwidget::make(),
            StreetWidget::make(),
            Buildingwidget::make(),

        ];
    }
}
