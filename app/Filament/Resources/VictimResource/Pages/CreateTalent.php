<?php

namespace App\Filament\Resources\VictimResource\Pages;

use App\Enums\talentType;
use App\Filament\Resources\VictimResource;
use App\Models\Talent;
use App\Models\VicTalent;
use App\Models\Victim;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Ramsey\Collection\Collection;

class CreateTalent extends Page
{
  use InteractsWithRecord;
    protected static string $resource = VictimResource::class;

    protected static string $view = 'filament.resources.victim-resource.pages.create-talent';

    public $vicTalent;
    public $talentData;

  public function mount(int | string $record): void
  {
    $this->record = $this->resolveRecord($record);
    $this->vicTalent=VicTalent::where('victim_id',$this->getRecord()->id)->get();
    if ($this->vicTalent->count()>0) {
      $this->talentForm->fill(['talent_id'=>$this->vicTalent->pluck('talent_id')]);
    }

    else $this->talentForm->fill([]);


  }
  protected function getForms(): array
  {

    return array_merge(parent::getForms(), [
      "talentForm" => $this->makeForm()
        ->model(VicTalent::class)
        ->schema($this->getTalentFormSchema())
        ->statePath('talentData'),
    ]);
  }

  protected function getTalentFormSchema(): array
  {
    return [
      Section::make()
        ->schema([
          Select::make('talent_id')
            ->relationship('Talent','name')
            ->searchable()
            ->label('الموهبة')
            ->preload()
            ->createOptionForm([
              Section::make('ادخال مواهب')
                ->schema([
                  TextInput::make('name')
                    ->required()
                    ->label('الموهبة')
                    ->maxLength(255),
                  Select::make('talentType')
                     ->label('التصنيف')
                     ->options(talentType::class),
                  ])
            ])
            ->editOptionForm([
              TextInput::make('name')
                ->required()
                ->label('الموهبة')
                ->maxLength(255),
                Select::make('talentType')
                    ->label('التصنيف')
                    ->options(talentType::class),
            ])
            ->columnSpan(3)
            ->multiple(),


          \Filament\Forms\Components\Actions::make([
            Action::make('store')
              ->label('تخزين')
              ->icon('heroicon-m-plus')
              ->button()

              ->color('success')
              ->requiresConfirmation()
              ->action(function () {
                VicTalent::where('victim_id',$this->getRecord()->id)->delete();
                foreach ($this->talentData['talent_id'] as $t_id) {
                    VicTalent::create([
                      'victim_id'=>$this->getRecord()->id,
                      'talent_id'=>$t_id,
                      ]);
                  }
              }),
          ])->extraAttributes(['class' => 'items-center justify-between']),

        ])
        ->columns(4)


    ];
  }




}
