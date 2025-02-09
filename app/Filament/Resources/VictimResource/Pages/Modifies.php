<?php

namespace App\Filament\Resources\VictimResource\Pages;

use App\Filament\Resources\VictimResource;
use App\Models\Bait;
use App\Models\Family;
use App\Models\Street;
use App\Models\Victim;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Modifies extends Page implements HasTable,HasForms
{
   use InteractsWithTable,InteractsWithForms;

    protected static string $resource = VictimResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.resources.victim-resource.pages.modifies';

  protected static ?string $navigationLabel='تعديلات';
  protected ?string $heading="";

  public $family_id;
  public $father_id;
  public $mother_id;

  public $street_id;
  public $newFamily_id;
    public $newFamilyshow_id;
  public $bait_id;
  public $withoutBait=false;
  public $newFather_id;
  public $newMother_id;
  public $newBait_id;
  public $familyData;

  public function mount(): void
  {
    $this->familyForm->fill(['withoutBait'=>$this->withoutBait]);
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
            ->afterStateUpdated(function ($state){
              $this->family_id=$state;
              $this->bait_id=null;
              $this->father_id=null;
              $this->mother_id=null;
            }),
          Select::make('bait_id')
            ->hiddenLabel()
            ->prefix('البيت')
            ->options(fn (Get $get): Collection => Bait::query()
              ->where('family_id', $get('family_id'))
              ->pluck('name', 'id'))
            ->preload()
            ->live()
            ->searchable()
            ->afterStateUpdated(function ($state){
              $this->bait_id=$state;
            }),
          Checkbox::make('withoutBait')
           ->afterStateUpdated(function ($state){
             $this->withoutBait=$state;
           })
            ->live()
           ->label('من غير بيت'),

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
            ->afterStateUpdated( function($state){
              $this->father_id=null;
              $this->mother_id=$state;
            }),


        ])->columns(5),
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
             ->afterStateUpdated(function ($state){
               $this->newFamily_id=$state;
               $this->newFamilyshow_id=Family::find($state)->familyshow_id;
             }),
           Select::make('newBait_id')
             ->hiddenLabel()

             ->prefix('البيت الجديد')
             ->options(function () { return Bait::query()
               ->where('family_id', $this->family_id)
               ->pluck('name', 'id'); })
             ->preload()
             ->live()
             ->searchable()
             ->afterStateUpdated(function ($state){
               $this->newBait_id=$state;
             }),
             Select::make('newFather_id')
                 ->hiddenLabel()
                 ->prefix('تعديل الأب')
                 ->prefixIcon('heroicon-m-pencil')
                 ->prefixIconColor('info')
                 ->options(Victim::where('is_father',1)->pluck('FullName','id'))
                 ->preload()
                 ->live()
                 ->searchable()

                 ->afterStateUpdated(function ($state){
                     $this->newFather_id=$state;
                 }),

             Select::make('newMother_id')
                 ->hiddenLabel()
                 ->prefix('تعديل الأم')
                 ->prefixIcon('heroicon-m-pencil')
                 ->prefixIconColor('info')

                 ->options(Victim::where('is_mother',1)->pluck('FullName','id'))
                 ->preload()
                 ->live()
                 ->searchable()

                 ->afterStateUpdated(function ($state){
                     $this->newMother_id=$state;
                 }),


         ])->columns(5)
    ];
  }

  public function table(Table $table):Table
  {
    return $table
      ->query(function (Victim $victim) {
        $victim = Victim::where('family_id',$this->family_id)
          ->when($this->bait_id,function ($q){
            $q->where('bait_id',$this->bait_id);
          })
          ->when($this->withoutBait,function ($q){
            $q->where('bait_id',null);
          })

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
        TextColumn::make('index')
          ->label('ت')
          ->rowIndex(),
        TextColumn::make('FullName')
          ->label('الاسم بالكامل')
          ->searchable(),
        TextColumn::make('Bait.name')
          ->label('البيت')
          ->searchable(),
        TextColumn::make('Family.FamName')
          ->sortable()
          ->toggleable()
          ->label('العائلة'),
        TextColumn::make('Street.StrName')
          ->toggleable()
          ->label('الشارغ'),
        TextColumn::make('sonOfFather.FullName')
          ->toggleable()
          ->label('الأب'),
        TextColumn::make('sonOfMother.FullName')
          ->sortable()
          ->toggleable()
          ->label('الأم'),
        TextColumn::make('husband.FullName')
          ->sortable()
          ->toggleable()
          ->label('الزوجة'),
        TextColumn::make('wife.FullName')
          ->sortable()
          ->toggleable()
          ->label('الزوج'),

        IconColumn::make('is_father')
            ->action(function (Victim $record): void {
                if ($record->is_father==1) $newFather=null;else $newFather=1;
                $record->update(['is_father'=>$newFather]);
            })
         ->boolean(),
        IconColumn::make('is_mother')
          ->action(function (Victim $record): void {
            if ($record->is_mother==1) $newMother=null;else $newMother=1;
            $record->update(['is_mother'=>$newMother]);
          })
          ->boolean(),

        TextColumn::make('male')
          ->label('الجنس')
          ->sortable()
          ->action(function (Victim $record): void {
            if ($record->male=='ذكر') $newMale='أنثي'; else $newMale='ذكر';
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
              ->deselectRecordsAfterCompletion()
            ->label('تعديل الشارع')
            ->hidden(!$this->street_id)
            ->requiresConfirmation()
            ->action(fn (Collection $records) => $records->each->update([
              'street_id'=>$this->street_id
            ])),
          BulkAction::make('editBait')
            ->deselectRecordsAfterCompletion()
            ->label('تعديل البيت')
            ->hidden(!$this->newBait_id)
            ->requiresConfirmation()
            ->action(fn (Collection $records) => $records->each->update([
              'bait_id'=>$this->newBait_id
            ])),
          BulkAction::make('editFamily')
              ->deselectRecordsAfterCompletion()
            ->label('تعديل العائلة')
            ->hidden(!$this->newFamily_id)
            ->requiresConfirmation()
            ->action(fn (Collection $records) => $records->each->update([
              'family_id'=>$this->newFamily_id,'familyshow_id'=>$this->newFamilyshow_id,
            ])),
        BulkAction::make('editFather')
            ->deselectRecordsAfterCompletion()
            ->label('تعديل الاب')
            ->hidden(!$this->newFather_id)
            ->requiresConfirmation()
            ->action(function (Collection $records) {
                $records->each(
                    fn (Model $record) => $record->update([
                        'father_id'=>$this->newFather_id,
                    ]),
                );
            }),

        BulkAction::make('delFather')
                ->deselectRecordsAfterCompletion()
                ->label('الغاء الاب')
                ->hidden(function(){
                    return  $this->father_id || $this->mother_id;
                })
                ->requiresConfirmation()
            ->action(function (Collection $records) {
                $records->each(
                    fn (Model $record) => $record->update([
                        'father_id'=>null,
                    ]),
                );
            }),
        BulkAction::make('editMother')
            ->deselectRecordsAfterCompletion()
            ->label('تعديل الأم')
            ->hidden(!$this->newMother_id)
            ->requiresConfirmation()
            ->action(fn (Collection $records) => $records->each->update([
                'mother_id'=>$this->newMother_id
            ])),


        ]),
          BulkAction::make('delMother')
              ->deselectRecordsAfterCompletion()
              ->label('الغاء الأم')
              ->hidden(function(){
                  return  $this->father_id || $this->mother_id;
              })
              ->requiresConfirmation()
              ->action(function (Collection $records) {
                  $records->each(
                      fn (Model $record) => $record->update([
                          'mother_id'=>null,
                      ]),
                  );
              }),

      ])
      ;
  }
}
