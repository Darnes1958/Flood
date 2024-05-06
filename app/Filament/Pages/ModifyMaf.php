<?php

namespace App\Filament\Pages;

use App\Models\Family;
use App\Models\Mafkoden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class ModifyMaf extends Page implements HasTable,HasForms
{
  use InteractsWithTable,InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.modify-maf';

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
        $mafkoden = Mafkoden::where('family_id',$this->family_id)
        ;
        return $mafkoden;
      })
      ->striped()
      ->columns([
        TextColumn::make('id')
          ->label('ت')
          ->rowIndex(),
        TextColumn::make('name')
          ->label('الاسم بالكامل')
          ->searchable(),
        TextColumn::make('Family.FamName')
          ->sortable()
          ->toggleable()
          ->label('العائلة'),
        TextColumn::make('sex')
          ->label('الجنس')
          ->sortable()

          ->badge()
          ->color(fn (int $state): string => match ($state) {
            1 => 'success',
            2 => 'Fuchsia',
          }),
      ])
      ->bulkActions([
        BulkActionGroup::make([

          BulkAction::make('editFamily')
            ->deselectRecordsAfterCompletion()
            ->label('تعديل العائلة')
            ->hidden(!$this->newFamily_id)
            ->requiresConfirmation()
            ->action(fn (Collection $records) => $records->each->update([
              'family_id'=>$this->newFamily_id
            ])),

        ]),
      ])
      ;
  }

}
