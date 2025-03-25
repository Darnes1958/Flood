<?php

namespace App\Filament\User\Pages;

use App\Filament\Widgets\GraetGrandFather;
use App\Filament\Widgets\GrandFather;
use App\Filament\Widgets\GrandSons;
use App\Filament\Widgets\Sons;
use Filament\Pages\Page;

class GrandTree extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.grand-tree';
    protected ?string $heading='';
    protected static ?string $navigationLabel='أجداد الأب والأم';
    protected static ?int $navigationSort=6;

    public function getFooterWidgetsColumns(): int | string | array
    {
        return 8;
    }

    protected function getFooterWidgets(): array
    {
        return [
            GraetGrandFather::class,
            Sons::class,
            GrandSons::class,

        ];
    }
}
