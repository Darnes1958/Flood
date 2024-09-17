<?php

namespace App\Filament\User\Pages;

use App\Livewire\TalentTypeWidget;
use App\Livewire\TalentVictimWidget;
use App\Livewire\TalentWidget;
use App\Models\Talent;
use App\Models\VicTalent;
use App\Models\Victim;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Madany extends Page
{


    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.madany';
    protected static ?string $navigationLabel='مجتمع مدني ومواهب';
    protected ?string $heading='';
    public function getFooterWidgetsColumns(): int | string | array
    {
        return 8;
    }

    protected function getFooterWidgets(): array
    {
        return [
            TalentTypeWidget::make(),
            TalentWidget::class,
            TalentVictimWidget::class,
        ];

    }
}
