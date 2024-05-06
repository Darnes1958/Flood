<?php

namespace App\Filament\Resources\MafkodenResource\Pages;

use App\Filament\Resources\MafkodenResource;
use App\Models\Family;
use App\Models\Mafkoden;
use App\Models\Street;
use App\Models\Victim;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ModifyMafkoden extends Page implements HasTable,HasForms
{
  use InteractsWithTable,InteractsWithForms;

    protected static string $resource = MafkodenResource::class;

    protected static string $view = 'filament.resources.mafkoden-resource.pages.modify-mafkoden';

  protected ?string $heading="";

  public $family_id;
  public $newFamily_id;
  public $familyData;

  public function mount(): void
  {
    $this->familyForm->fill([]);
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
            ->options(Family::all()->pluck('FamName','id'))
            ->preload()
            ->live()
            ->searchable()
            ->columnSpan(2)
            ->afterStateUpdated(function ($state){
              $this->family_id=$state;

            }),

          Select::make('newFamily_id')
            ->hiddenLabel()
            ->prefix('العائلة الجديدة')
            ->prefixIcon('heroicon-m-pencil')
            ->prefixIconColor('info')

            ->options(Family::all()->pluck('FamName','id'))
            ->preload()
            ->live()
            ->searchable()
            ->columnSpan(2)
            ->afterStateUpdated(function ($state){
              $this->newFamily_id=$state;
            }),
        ])->columns(6)
    ];
  }
  public function table(Table $table):Table
  {
    return $table
      ->query(function (Mafkoden $mafkoden) {
        $mafkoden = Mafkoden::where('family_id',$this->family_id)->where('nation','ليبيا')
          ;
        return $mafkoden;
      })
      ->striped()
      ->columns([
        TextColumn::make('ser')
          ->label('ت'),
        TextColumn::make('id')
          ->label('الرقم الألي')
          ->sortable(),
        TextColumn::make('name')
          ->label('الاسم بالكامل')
          ->searchable(isIndividual: true),
        TextColumn::make('Family.FamName')
          ->sortable()
          ->toggleable()
          ->label('العائلة'),
        TextColumn::make('sex')
          ->state(function (Mafkoden $record): string {
            if ($record->sex==1) return 'ذكر';
            if ($record->sex==2) return 'أنثي';
          })
          ->action(function (Mafkoden $record): void {
            if ($record->sex==1) $newMale=2; else $newMale=1;
            $record->update(['sex'=>$newMale]);
          })
          ->label('الجنس')
          ->badge()
          ->color(fn (string $state): string => match ($state) {
            'ذكر' => 'success',
            'أنثي' => 'Fuchsia',
          }),
        TextColumn::make('who')
          ->searchable(isIndividual: true)
          ->label('المبلغ'),
        TextColumn::make('mother')
          ->searchable(isIndividual: true)
          ->label('الام'),

      ])
      ->bulkActions([


          BulkAction::make('editFamily')
            ->deselectRecordsAfterCompletion()
            ->label('تعديل العائلة')
            ->hidden(!$this->newFamily_id)

            ->action(fn (Collection $records) => $records->each->update([
              'family_id'=>$this->newFamily_id
            ])),


      ])
      ;
  }
}
