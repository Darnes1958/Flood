<?php

namespace App\Filament\User\Pages;

use App\Filament\Widgets\GraetGrandFather;
use App\Filament\Widgets\GrandFather;
use App\Filament\Widgets\Sons;
use Filament\Pages\Page;

class BigestGrand extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.bigest-grand';
    protected ?string $heading='';
    protected static ?string $navigationLabel='أكبر الأسر';
    protected static ?int $navigationSort=6;
    public function getFooterWidgetsColumns(): int | string | array
    {
        return 5;
    }

    protected function getFooterWidgets(): array
    {
        return [

            GrandFather::class,
            Sons::class,
        ];
    }
}
