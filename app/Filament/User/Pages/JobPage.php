<?php

namespace App\Filament\User\Pages;

use App\Livewire\JobTypeWidget;
use App\Livewire\JobVictimWidget;
use App\Livewire\JobWidget;
use App\Livewire\TalentTypeWidget;
use App\Livewire\TalentVictimWidget;
use App\Livewire\TalentWidget;
use Filament\Pages\Page;

class JobPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.job-page';
    protected static ?string $navigationLabel='وظائف ومهن';
    protected static ?int $navigationSort=9;
    protected ?string $heading='';
    public function getFooterWidgetsColumns(): int | string | array
    {
        return 8;
    }

    protected function getFooterWidgets(): array
    {
        return [
            JobTypeWidget::make(),
            JobWidget::class,
            JobVictimWidget::class,
        ];

    }
}
