<?php

namespace App\Filament\User\Pages;

use App\Models\Archif;
use App\Models\Bait;
use App\Models\Bedon;
use App\Models\Family;
use App\Models\Mafkoden;
use App\Models\Street;
use App\Models\Tasreeh;
use App\Models\Victim;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UserInfoPage extends Page implements HasTable,HasForms
{
  use InteractsWithTable,InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.user-info-page';

  protected ?string $heading='';
  protected static ?string $navigationLabel='استفسار وبحث عن ضحايا الفيضان';
  protected static ?int $navigationSort=1;


  public $family_id=null;

  public $street_id=null;
  public $show='all';
  public $mother;
  public $count;



  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Section::make()
          ->schema([
            Select::make('family_id')
              ->hiddenLabel()
              ->prefix('العائلة')
              ->options(Family::query()
              ->pluck('FamName', 'id'))
              ->preload()
              ->live()
              ->searchable()
              ->columnSpan(2)
              ->afterStateUpdated(function ($state){
                $this->family_id=$state;
                $this->mother=Victim::where('family_id',$state)->where('is_mother',1)->pluck('id')->all();
              }),
            Select::make('street_id')
              ->hiddenLabel()
              ->prefix('العنوان')
              ->options(Street::all()->pluck('StrName','id'))
              ->preload()
              ->live()
              ->searchable()
              ->columnSpan(3)
              ->afterStateUpdated(function ($state){
                $this->street_id=$state;
              }),


            Radio::make('show')
              ->inline()
              ->hiddenLabel()
              ->inlineLabel(false)
              ->reactive()
              ->live()
              ->columnSpan(4)
              ->default('all')
              ->afterStateUpdated(function ($state){
                $this->show=$state;
              })
              ->options([
                'all'=>'الكل',
                'parent'=> 'أباء وأمهات',
                'single'=>'افراد',
                'father_only'=>'أباء',
                'mother_only'=>'أمهات',
              ]),
          ])
          ->columns(8),
      ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->query(function (){
        return
          Victim::query()
            ->where('notes','!=',null)
            ->when($this->family_id,function($q){
              $q->where('family_id',$this->family_id);
            })
            ->when($this->street_id,function($q){
              $q->where('street_id',$this->street_id);
            })
            ->when($this->show=='parent',function ($q){
              $q->where(function ($q){
                $q->where('is_father',1)
                  ->orwhere('is_mother',1);
              });
            })
            ->when($this->show=='father_only',function ($q){
              $q->where('is_father',1);
            })
            ->when($this->show=='mother_only',function ($q){
              $q->where('is_mother',1);
            })

            ->when($this->show=='single',function ($q){
              $q->where(function ($q){
                $q->where('is_father',null)->orwhere('is_father',0);
              })
                ->where(function ($q){
                  $q->where('is_mother',null)->orwhere('is_mother',0);
                })
                ->where(function ($q){
                  $q->where('father_id',null)->orwhere('father_id',0);
                })
                ->when($this->family_id,function ($q){

                  $q->where(function ( $query) {
                    $query->where('mother_id', null)
                      ->orwhere('mother_id', 0)
                      ->orwhereNotIn('mother_id',$this->mother);
                  });
                });
            });
      })
      ->columns([
        TextColumn::make('FullName')
          ->label('الاسم بالكامل')
          ->sortable()
          ->searchable()
          ->description(function (Victim $record){
            $who='';
            if (!$record->sonOfMother) {

              $bed = null;
              $maf = null;

              if ($record->bedon) $bed = Bedon::find($record->bedon);
              if ($record->mafkoden) $maf = Mafkoden::find($record->mafkoden);

              if ($bed || $maf) {
                if ($bed && $bed->mother) $who = 'الأم : ' . $bed->mother;
                else {
                  if ($maf && $maf->mother) $who = $who . 'الأم : ' . $maf->mother;
                }
              }
            }
            if ($record->notes) $who=$who.' ('.$record->notes.')';
            return $who;
          })

          ->formatStateUsing(fn (Victim $record): View => view(
            'filament.user.pages.full-data',
            ['record' => $record],
          ))
          ->searchable(),
        TextColumn::make('Family.FamName')
          ->label('العائلة')
          ->sortable()
          ->toggleable()
          ->hidden(function (){return $this->family_id!=null;})
          ->searchable(),

        TextColumn::make('Street.StrName')
          ->label('العنوان')
          ->toggleable()
          ->sortable()
          ->searchable(),



      ])
      ;
  }
}