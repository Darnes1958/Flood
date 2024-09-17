<?php

namespace App\Filament\User\Pages;

use App\Filament\Widgets\WorkWidget;
use Filament\Pages\Page;

class AtWork extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.at-work';
    protected ?string $heading='';
    protected static ?string $navigationLabel='في العمل';
    protected static ?int $navigationSort=8;

    protected function getFooterWidgets(): array
    {
        return [
            WorkWidget::class,
        ];
    }
}
