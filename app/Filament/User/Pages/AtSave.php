<?php

namespace App\Filament\User\Pages;

use App\Filament\Widgets\SaveWidget;

use Filament\Pages\Page;

class AtSave extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.at-save';
    protected ?string $heading='';
    protected static ?string $navigationLabel='منقذين';
    protected static ?int $navigationSort=6;

    protected function getFooterWidgets(): array
    {
        return [
            SaveWidget::class,
        ];
    }
}
