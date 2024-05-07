<?php

namespace App\Filament\Resources\MafkodenResource\Pages;

use App\Filament\Resources\MafkodenResource;
use App\Livewire\MafkodenWidget;
use App\Livewire\VictimWidget;
use App\Models\Family;
use App\Models\Mafkoden;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\VerticalAlignment;
use Livewire\Attributes\On;

class CompareMaf extends Page implements HasForms
{
  use InteractsWithForms;
    protected static string $resource = MafkodenResource::class;

    protected static string $view = 'filament.resources.mafkoden-resource.pages.compare-maf';
  protected ?string $heading="";

  public $family_id;
  public $with_victim=false;
  public $show_description=false;
  public $show_other=false;

  public $familyData;

  public function mount(): void
  {
      $this->familyForm->fill(['show_other'=>true,]);
  }

  protected function getForms(): array
  {
    return array_merge(parent::getForms(), [
      "familyForm" => $this->makeForm()
        ->model(Family::class)
        ->schema($this->getFamilyFormSchema())
        ->statePath('familyData'),

    ]);
  }

  protected function getFamilyFormSchema(): array
  {
    return [
      Section::make()
        ->schema([
          Select::make('family_id')
            ->hiddenLabel()
            ->prefix('العائلة')

            ->optionsLimit(500)
            ->options(Family::all()->pluck('FamName','id'))
            ->preload()
            ->live()
            ->searchable()
            ->columnSpan(4)
            ->afterStateUpdated(function ($state){
              $this->family_id=$state;
              $this->dispatch('updatefamily', family_id: $this->family_id,with_victim : $this->with_victim,
                  show_description: $this->show_description,who: 'maf',show_other: $this->show_other );
            }),
        Checkbox::make('with_victim')
          ->label('إظهار المتطابق')
          ->reactive()

          ->afterStateUpdated(function ($state){
            $this->with_victim=$state;
            $this->dispatch('updatefamily', family_id: $this->family_id,with_victim : $this->with_victim,
                show_description: $this->show_description,who: 'maf',show_other: $this->show_other);
          }),
        Checkbox::make('show_description')
          ->label('إظهار التفاصيل')
          ->reactive()

          ->afterStateUpdated(function ($state){
            $this->show_description=$state;
            $this->dispatch('updatefamily', family_id: $this->family_id,with_victim : $this->with_victim,
                show_description: $this->show_description,who: 'maf',show_other: $this->show_other);
          }),
             Checkbox::make('show_other')
                 ->label('إظهار بتصريح')
                 ->reactive()

                 ->afterStateUpdated(function ($state){
                     $this->show_other=$state;
                     $this->dispatch('updatefamily', family_id: $this->family_id,with_victim : $this->with_victim,
                         show_description: $this->show_description,who: 'maf',show_other: $this->show_other);
                 })

        ])->columns(8)
    ];
  }

  public static function getWidgets(): array
  {
    return [
      MafkodenWidget::class,
      VictimWidget::class,
    ];
      }
  protected function getFooterWidgets(): array
  {
    return [
      MafkodenWidget::make([
        'family_id'=>$this->family_id,'without'=>$this->with_victim,
      ]),
      VictimWidget::make([
        'family_id'=>$this->family_id,'without'=>$this->with_victim,'who'=>'maf','show_other'=> $this->show_other,
      ])
    ];
  }
}
