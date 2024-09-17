<?php

namespace App\Filament\User\Pages;

use App\Filament\Widgets\MaleFemale;
use Filament\Pages\Page;

class Counts extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.counts';

    protected ?string $heading='';
    protected static ?string $navigationLabel='الأعداد';
    protected static ?int $navigationSort=2;

    protected function getFooterWidgets(): array
    {
       return [
           MaleFemale::class,
       ];
    }
}
