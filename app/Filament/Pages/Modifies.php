<?php

namespace App\Filament\Pages;

use App\Models\Family;
use App\Models\Street;
use App\Models\Victim;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class Modifies extends Page implements HasTable,HasForms
{
   use InteractsWithTable,InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.modifies';

  protected static ?string $navigationLabel='تعديلات';
  protected ?string $heading="";

  public $family_id;
  public $father_id;
  public $mother_id;

  public $street_id;
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
          Select::make('father_id')
            ->hiddenLabel()
            ->prefix('الأب')
            ->options(fn (Get $get): Collection => Victim::query()
              ->where('family_id',$this->family_id)
              ->where('is_father',1)
              ->distinct()
              ->pluck('FullName', 'id'))
            ->preload()
            ->live()
            ->searchable()
            ->columnSpan(2)
            ->afterStateUpdated( function($state){
              $this->mother_id=null;
              $this->father_id=$state;
            }),
          Select::make('mother_id')
            ->hiddenLabel()
            ->prefix('الأم')
            ->options(fn (Get $get): Collection => Victim::query()
              ->where('family_id',$this->family_id)
              ->where('is_mother',1)
              ->distinct()
              ->pluck('FullName', 'id'))
            ->preload()
            ->live()
            ->searchable()
            ->columnSpan(2)
            ->afterStateUpdated( function($state){
              $this->father_id=null;
              $this->mother_id=$state;
            }),


        ])->columns(6),
      Section::make()
         ->schema([

           Select::make('street_id')
             ->hiddenLabel()
             ->prefix('الشارع الجديد')
             ->prefixIcon('heroicon-m-pencil')
             ->prefixIconColor('info')
             ->options(Street::all()->pluck('StrName','id'))
             ->preload()
             ->live()
             ->searchable()
             ->columnSpan(2)
             ->afterStateUpdated(function ($state){
               $this->street_id=$state;
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
      ->query(function (Victim $victim) {
        $victim = Victim::where('family_id',$this->family_id)
        ->when($this->father_id,function ($q){
          $q->where('id',$this->father_id)
            ->orwhere('father_id',$this->father_id)
            ->orwhere('husband_id',$this->father_id);
        })
        ->when($this->mother_id,function ($q){
            $q->where('id',$this->mother_id)
              ->orwhere('mother_id',$this->mother_id)
              ->orwhere('wife_id',$this->mother_id);
          });
        return $victim;
      })
      ->striped()


      ->columns([

        TextColumn::make('FullName')
          ->label('الاسم بالكامل')
          ->searchable(),
        TextColumn::make('Street.StrName')
          ->toggleable()
          ->label('الشارع'),
        TextColumn::make('Street.Area.AreaName')
          ->toggleable()
          ->label('المحلة'),
        TextColumn::make('Family.FamName')
          ->sortable()
          ->toggleable()
          ->label('العائلة'),
        TextColumn::make('Family.Tribe.TriName')
          ->sortable()
          ->toggleable()
          ->label('القبيلة'),
        IconColumn::make('is_father')
         ->boolean(),
        IconColumn::make('is_mother')

          ->action(function (Victim $record): void {
            if ($record->male=='ذكر') return;
            if (($record->husband_id || Victim::where('mother_id',$record->id)->exists()) && $record->is_mother) return;
            if ($record->is_mother==1) $newMother=null;else $newMother=1;
            $record->update(['is_mother'=>$newMother]);
          })
          ->boolean(),

        TextColumn::make('male')
          ->label('الجنس')
          ->sortable()
          ->action(function (Victim $record): void {
            if ($record->male=='ذكر') $newMale='أنثي'; else $newMale='ذكر';
            if ($record->is_father==1 && $newMale=='أنثي') return;
            if ($record->is_mother==1 && $newMale=='ذكر') return;
            $record->update(['male'=>$newMale]);
          })
          ->badge()
          ->color(fn (string $state): string => match ($state) {
            'ذكر' => 'success',
            'أنثي' => 'Fuchsia',
          }),
      ])
      ->bulkActions([
        BulkActionGroup::make([
          BulkAction::make('editStreet')
            ->label('تعديل الشارع')

            ->hidden(!$this->street_id)
            ->requiresConfirmation()
            ->action(fn (Collection $records) => $records->each->update([
              'street_id'=>$this->street_id
            ])),
          BulkAction::make('editFamily')
            ->label('تعديل العائلة')
            ->hidden(!$this->family_id)
            ->requiresConfirmation()
            ->action(fn (Collection $records) => $records->each->update([
              'family_id'=>$this->newFamily_id
            ])),

        ])
      ])
      ;
  }
}
