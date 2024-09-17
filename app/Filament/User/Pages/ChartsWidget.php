<?php

namespace App\Filament\User\Pages;

use App\Filament\Widgets\ChartCategorie;
use App\Filament\Widgets\ChartEastWest;
use App\Filament\Widgets\ChartNation;
use App\Filament\Widgets\ChartParent;
use App\Filament\Widgets\ChartRoad;
use App\Filament\Widgets\ChartYear;
use App\Filament\Widgets\YearWidget;
use Filament\Pages\Page;

class ChartsWidget extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.charts-widget';
    protected ?string $heading='';
    protected static ?string $navigationLabel='رسوم بيانية';
    protected static ?int $navigationSort=10;

    protected function getFooterWidgets(): array
    {
        return [
            ChartEastWest::class,
            ChartNation::class,
            ChartParent::class,
            ChartRoad::class,
            YearWidget::class,
            ChartYear::class,
            ChartCategorie::class,
        ];
    }
}
