<?php

namespace App\Filament\User\Pages;

use App\Models\Archif;
use App\Models\Bait;
use App\Models\Bedon;
use App\Models\BigFamily;
use App\Models\Family;
use App\Models\Mafkoden;
use App\Models\Street;
use App\Models\Tarkeba;
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
use Filament\Tables\Columns\ImageColumn;
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
  public $big_family=null;
  public $tarkeba=null;
  public $families;
  public $big_families;

  public $street_id=null;
  public $show='all';
  public $mother;
  public $count;
  public $notes=true;



  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Section::make()
          ->schema([

            Select::make('family_id')
              ->hiddenLabel()
              ->prefix('العائلة')
              ->options(function () {
                  if ($this->tarkeba || $this->big_families)
                     return Family::query()->whereIn('id',$this->families)->pluck('FamName', 'id');
                  return Family::query()->pluck('FamName', 'id');
              })
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
              ->columnSpan(2)
              ->afterStateUpdated(function ($state){
                $this->street_id=$state;
              }),


            Radio::make('show')
              ->inline()
              ->hiddenLabel()
              ->inlineLabel(false)
              ->reactive()
              ->live()
              ->columnSpan(3)
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
              Checkbox::make('notes')
                  ->inlineLabel(false)
                  ->live()
                  ->default(0)
                  ->afterStateUpdated(function ($state){
                      $this->notes=$state;
                  })
                  ->label('إظهار الملاحظات'),
              Select::make('tarkeba')
                  ->hiddenLabel()
                  ->prefix('التركيبة الاجتماعية')
                  ->options(Tarkeba::query()
                      ->pluck('name', 'id'))
                  ->preload()
                  ->live()
                  ->searchable()
                  ->columnSpan(2)
                  ->afterStateUpdated(function ($state){
                      $this->tarkeba=$state;
                      $this->big_families=BigFamily::where('tarkeba_id',$state)->pluck('id')->all();
                      $this->families=Family::whereIn('big_family_id',$this->big_families)->pluck('id')->all();
                      $this->mother=Victim::whereIn('family_id',$this->families)->where('is_mother',1)->pluck('id')->all();
                      $this->family_id=null;
                      $this->big_family=null;

                  }),
              Select::make('big_family')
                  ->hiddenLabel()
                  ->prefix('العائلة الكبري')
                  ->options(function () {
                   if ($this->tarkeba)
                      return BigFamily::query()->where('tarkeba_id',$this->tarkeba)
                          ->pluck('name', 'id');
                      return BigFamily::query()
                          ->pluck('name', 'id');
                  })
                  ->preload()
                  ->live()
                  ->searchable()
                  ->columnSpan(2)
                  ->afterStateUpdated(function ($state){
                      $this->big_family=$state;
                      $this->families=Family::where('big_family_id',$state)->pluck('id')->all();
                      $this->mother=Victim::whereIn('family_id',$this->families)->where('is_mother',1)->pluck('id')->all();
                      $this->family_id=null;
                  }),
              \Filament\Forms\Components\Actions::make([
                  \Filament\Forms\Components\Actions\Action::make('printFamily')
                      ->label('طباعة')
                      ->visible(function (Get $get){
                          return $get('family_id')!=null;
                      })
                      ->icon('heroicon-m-printer')
                      ->url(function (Get $get) {
                          return route('pdffamily',
                              ['family_id' => $get('family_id'),
                                  'bait_id' => 0,]);
                      } ),
                  \Filament\Forms\Components\Actions\Action::make('printBigFamily')
                      ->label('طباعة الكبري')
                      ->visible(function (Get $get){
                          return $get('big_family')!=null;
                      })
                      ->color('success')
                      ->icon('heroicon-m-printer')
                      ->url(function (Get $get) {
                          return route('pdfbigfamily',
                              ['big_family' => $get('big_family'),]);
                      } ),
                  ])

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
            ->when($this->tarkeba || $this->big_families,function ($q){
                $q->orderby('family_id');
            })
            ->when($this->family_id && !$this->big_family,function($q){
              $q->where('family_id',$this->family_id);
            })
            ->when($this->big_family || $this->tarkeba,function($q){
                  $q->whereIn('family_id',$this->families);
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
            if (!$this->notes) return null;
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
          ImageColumn::make('image')
              ->toggleable()

              ->label('')
              ->circular(),

      ])
      ;
  }
}
