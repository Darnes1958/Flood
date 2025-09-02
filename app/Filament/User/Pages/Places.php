<?php

namespace App\Filament\User\Pages;

use App\Livewire\AreaWidget;
use App\Livewire\Buildingwidget;
use App\Livewire\Roadwidget;
use App\Livewire\StreetWidget;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use function Livewire\on;

class Places extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.places';
    protected ?string $heading='';
    protected static ?string $navigationLabel='العناوين';
    protected static ?int $navigationSort=5;
    public function getFooterWidgetsColumns(): int | string | array
    {
        return 3;
    }
    public $show='area';
    public $onlyLibyan=false;

    public function mount(): void{
        $this->form->fill();
    }
public function form(Form $form): Form
{
    return $form
        ->schema([
            Radio::make('show')
                ->inline()
                ->hiddenLabel()
                ->inlineLabel(false)
                ->live()
                ->columnSpan(3)
                ->default('area')
                ->afterStateUpdated(function ($state){
                    $this->show=$state;
                    $this->dispatch('take_road',road_id: null,areaName: null);
                    $this->dispatch('take_area',area_id: null,areaName: null);

                })
                ->options([
                    'area'=>'بالمحلات',
                    'road'=> 'بالشوارع الرئيسية',
                    'two'=>'بالمحلات والشوارع',
                ]),
            Checkbox::make('onlyLibyan')
                ->live()
                ->afterStateUpdated(function ($state){
                    $this->onlyLibyan=$state;
                    $this->dispatch('take_libyan',onlyLibyan: $this->onlyLibyan);
                })
        ]);
}

    protected function getFooterWidgets(): array
    {
        if ($this->show=='area')
        return [
            AreaWidget::make(),
            StreetWidget::make(),
            Buildingwidget::make(),


        ];
        if ($this->show=='road')
            return [
                Roadwidget::make(),
                StreetWidget::make(),
                Buildingwidget::make(),
            ];
        if ($this->show=='two')
            return [
                AreaWidget::make(),
                StreetWidget::make(),
                Buildingwidget::make(),
                Roadwidget::make(),
            ];

    }
}
