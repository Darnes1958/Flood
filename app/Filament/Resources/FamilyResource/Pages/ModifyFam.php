<?php

namespace App\Filament\Resources\FamilyResource\Pages;

use App\Filament\Resources\FamilyResource;
use App\Models\Bedon;
use App\Models\BigFamily;
use App\Models\Family;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class ModifyFam extends Page implements HasTable,HasForms
{
  use \Filament\Tables\Concerns\InteractsWithTable, \Filament\Forms\Concerns\InteractsWithForms;
    protected static string $resource = FamilyResource::class;
    protected ?string $heading='';


    protected static string $view = 'filament.resources.family-resource.pages.modify-fam';

  public  $big_family_id=null;
  public $familyData;

  public function mount(): void
  {
    $this->familyForm->fill([]);
  }
  protected function getForms(): array
  {
    return array_merge(parent::getForms(), [
      "familyForm" => $this->makeForm()
        ->model(BigFamily::class)
        ->schema($this->getFamilyFormSchema())
        ->statePath('familyData'),

    ]);

  }

  protected function getFamilyFormSchema(): array
  {
    return [
      Section::make()
        ->schema([
          Select::make('big_family_id')
            ->options(BigFamily::all()->pluck('name','id'))
            ->label('')
            ->searchable()
            ->preload()
            ->columnSpan(2)
            ->afterStateUpdated(function ($state){
              $this->big_family_id=$state;

            }),
        ])->columns(6)

    ];
      }

      public function table(Table $table): Table
      {
        return $table
          ->query(function (Family $mafkoden) {
            $mafkoden = Family::where('id','!=',null)
            ;
            return $mafkoden;
          })
          ->striped()
          ->columns([
            TextColumn::make('FamName')
              ->searchable()
              ->label('اسم العائلة'),
            TextColumn::make('Big_family.name')
              ->searchable()
              ->label('القبيلة'),
            TextColumn::make('Tribe.TriName')
              ->searchable()
              ->label('القبيلة 2'),

          ])
          ->bulkActions([
            BulkAction::make('editFamily')
              ->deselectRecordsAfterCompletion()
              ->label('تعديل العائلة')
              ->hidden(!$this->big_family_id)

              ->action(fn (Collection $records) => $records->each->update([
                'big_family_id'=>$this->big_family_id,
              ])),

          ])
          ;
      }

}
