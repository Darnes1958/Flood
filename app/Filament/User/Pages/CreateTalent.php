<?php

namespace App\Filament\User\Pages;

use App\Enums\talentType;

use App\Models\VicTalent;
use App\Models\Victim;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;


use Filament\Pages\Page;
use Livewire\Attributes\On;

class CreateTalent extends Page
{
    protected ?string $heading='';
    public static function shouldRegisterNavigation(): bool
    {
        return  false;
    }

    protected static string $view = 'filament.user.pages.create-talent';

    public $vicTalent;
    public $talentData;
    public $id;

    #[On('fillModal')]
    public function fillModal($id){
        $this->id=$id;
        $this->vicTalent=VicTalent::where('victim_id',$id)->get();
        if ($this->vicTalent->count()>0) {
            $this->talentForm->fill(['talent_id'=>$this->vicTalent->pluck('talent_id')]);
        }

        else $this->talentForm->fill([]);
    }
  public function mount(): void
  {
     $this->id=Victim::first()->id;
      $this->vicTalent=VicTalent::where('victim_id',$this->id)->get();
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
              ->action(function () {
                VicTalent::where('victim_id',$this->id)->delete();
                foreach ($this->talentData['talent_id'] as $t_id) {
                    VicTalent::create([
                      'victim_id'=>$this->id,
                      'talent_id'=>$t_id,
                      ]);
                  }
               $this->dispatch('resetInfo');

              }),
          ])->extraAttributes(['class' => 'items-center justify-between']),

        ])
        ->columns(4)


    ];
  }




}
