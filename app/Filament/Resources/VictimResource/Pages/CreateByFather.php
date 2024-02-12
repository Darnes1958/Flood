<?php

namespace App\Filament\Resources\VictimResource\Pages;

use App\Filament\Resources\VictimResource;
use App\Models\Family;
use App\Models\Street;
use App\Models\Victim;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;

use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CreateByFather extends Page implements HasTable
{
  use \Filament\Tables\Concerns\InteractsWithTable;


    protected static string $resource = VictimResource::class;

    protected static string $view = 'filament.resources.victim-resource.pages.create-by-father';

    protected ?string $heading="";

    public $family_id='';
    public $father_id='';
    public $mother_id='';

    public $family;
    public $victim;
    public $victimData;
    public $familyData;

    public $openName=false;
    public $openInfo=false;
    public $openParaent=false;

    public $name1;
    public $name2;
    public $name3;




  public function mount(): void
  {
    $this->family_id=Family::where('FamName','غير محدد')->first()->id;
    $this->familyForm->fill([]);
    $this->victimForm->fill([
      'male'=>'ذكر',
      'family_id'=>$this->family_id,
      'street_id'=>Street::where('StrName','غير محدد')->first()->id,
      ]);

  }

  protected function getForms(): array
  {
    return array_merge(parent::getForms(), [
      "familyForm" => $this->makeForm()
        ->model(Family::class)
        ->schema($this->getFamilyFormSchema())
        ->statePath('familyData'),
      "victimForm" => $this->makeForm()
        ->model(Victim::class)
        ->schema($this->getVictimFormSchema())
        ->statePath('victimData'),

    ]);
  }

  public function Store($tag){
    if ($tag==1 && !filled($this->victimData['Name2']))  {$this->dispatch('gotoitem', test: 'Name2'); return;}
    if ($tag==2 ) {$this->dispatch('gotoitem', test: 'Name3');return;}
    if ($tag==3) {$this->dispatch('gotoitem', test: 'Name4');return;}
    $this->validate();

    $this->victim=Victim::create($this->victimData);
    if ($this->victim->wife_id) Victim::find($this->victim->wife_id)->update(['husband_id'=>$this->victim->id]);
    if ($this->victim->husband_id) Victim::find($this->victim->husband_id)->update(['wife_id'=>$this->victim->id]);
    if ($this->victim->mother_id) Victim::find($this->victim->mother_id)->update(['is_mother'=>1]);
    if ($this->victim->father_id) Victim::find($this->victim->father_id)->update(['is_father'=>1]);

    $this->victimForm->fill([
      'Name1'=>'',
      'Name2'=>$this->victimData['Name2'],'Name3'=>$this->victimData['Name3'],'Name4'=>$this->victimData['Name4'],
      'family_id'=>$this->victimData['family_id'],'street_id'=>$this->victimData['street_id'],
      'father_id'=>$this->victimData['father_id'],'mother_id'=>$this->victimData['mother_id'],
      'male'=>'ذكر',
    ]);
    $this->dispatch('gotoitem', test: 'Name1');
  }
  protected function getVictimFormSchema(): array
  {
    return [
      Section::make()
        ->schema([
          Toggle::make('is_father')
            ->onColor(function (Get $get){
              if ($get('male')=='ذكر') return 'success';
              else return 'gray';})
            ->offColor(function (Get $get){
              if ($get('male')=='ذكر') return 'danger';
              else return 'gray';})
            ->disabled(fn(Get $get): bool=>$get('male')=='أنثي')
            ->label('أب'),
          Toggle::make('is_mother')
            ->onColor(function (Get $get){
              if ($get('male')=='أنثي') return 'success';
              else return 'gray';})
            ->offColor(function (Get $get){
              if ($get('male')=='أنثي') return 'danger';
              else return 'gray';})
            ->disabled(fn(Get $get): bool=>$get('male')=='ذكر')
            ->label('أم'),
          Radio::make('male')
            ->label('الجنس')
            ->inline()
            ->default('ذكر')
            ->columnSpan(2)
            ->reactive()
            ->required()

            ->afterStateUpdated(function(Set $set,$state) {
              if ($state=='ذكر')  $set('is_mother',0);
              else $set('is_father',0);})
            ->options([
              'ذكر' => 'ذكر',
              'أنثي' => 'أنثى',
            ]),
          Select::make('family_id')
            ->label('العائلة')
            ->required()
            ->relationship('Family','FamName')
            ->searchable()
            ->createOptionForm([
              TextInput::make('FamName')
                ->required()
                ->label('اسم العائلة')
                ->unique()
                ->maxLength(255),
              Select::make('tribe_id')
                ->relationship('Tribe','TriName')
                ->label('القبيلة')

                ->searchable()
                ->preload()
                ->createOptionForm([
                  TextInput::make('TriName')
                    ->required()
                    ->label('اسم القبيلة')
                    ->maxLength(255)
                    ->unique()
                    ->required(),
                ])
                ->reactive()
                ->required(),
            ])
            ->editOptionForm([
              TextInput::make('FamName')
                ->required()
                ->label('اسم العائلة')
                ->maxLength(255),
              Select::make('tribe_id')
                ->relationship('Tribe','TriName')
                ->label('القبيلة')
                ->searchable()
                ->preload()
                ->reactive()
                ->required(),
            ])
            ->afterStateUpdated(function ($state){
              $this->family_id=$state;
            })
            ->preload(),

          Select::make('husband_id')
            ->label('زوجة')
            ->relationship('husband', 'FullName', fn (Builder $query) => $query
              ->where('male','ذكر'))
            ->searchable()
            ->reactive()
            ->preload()
            ->visible(fn (Get $get) => $get('male') == 'أنثي'),
          Select::make('wife_id')
            ->label('زوج')
            ->relationship('wife','FullName', fn (Builder $query) => $query
              ->where('male','أنثي'))
            ->searchable()
            ->reactive()
            ->preload()
            ->visible(fn (Get $get) => $get('male') == 'ذكر'),
          Select::make('father_id')
            ->label('والده')
            ->relationship('sonOfFather','FullName', fn (Builder $query) => $query
              ->where('male','ذكر'))
            ->searchable()
            ->preload(),
          Select::make('mother_id')
            ->label('والدته')
            ->relationship('sonOfMother','FullName', fn (Builder $query) => $query
              ->where('male','أنثي'))
            ->searchable()
            ->preload(),
          TextInput::make('Name1')
            ->label('الإسم الاول')
            ->afterStateUpdated(function (Set $set,$state,Get $get) {
              $set('FullName',$state.' '.$get('Name2').' '.$get('Name3').' '.$get('Name4'));
              $this->name1=$state;
            })
            ->live(onBlur: true)
            ->extraAttributes([
              'wire:keydown.enter' => "Store(1)",
            ])
            ->id('Name1')
            ->required(),
          TextInput::make('Name2')
            ->label('الإسم الثاني')
            ->afterStateUpdated(function (Set $set,$state,Get $get) {
              $set('FullName',$get('Name1').' '.$get('Name2').' '.$get('Name3').' '.$get('Name4'));
              $this->name2=$state;
            })
            ->extraAttributes(['wire:keydown.enter' => "Store(2)",])
            ->id('Name2')
            ->required(),
          TextInput::make('Name3')
            ->afterStateUpdated(function (Set $set,$state,Get $get) {
              $set('FullName',$get('Name1').' '.$get('Name2').' '.$get('Name3').' '.$get('Name4'));
              $this->name3=$state;
            })
            ->extraAttributes(['wire:keydown.enter' => "Store(3)",])
            ->id('Name3')
            ->label('الإسم الثالث'),
          TextInput::make('Name4')
            ->afterStateUpdated(function (Set $set,$state,Get $get) {
              $set('FullName',$get('Name1').' '.$get('Name2').' '.$get('Name3').' '.$get('Name4')); })
            ->extraAttributes(['wire:keydown.enter' => "Store(4)",])
            ->id('Name4')
            ->label('الإسم الرابع'),

          Select::make('street_id')
            ->label('الشارع')
            ->relationship('Street','StrName')
            ->searchable()
            ->preload()
            ->required(),

          TextInput::make('FullName')
            ->label('الاسم بالكامل')
            ->unique()
            ->columnSpan(2)
            ->disabled(),

        ])->columns(4)
    ];
  }

  public function GoFather(){
    $this->openName=false;
    $this->openInfo=false;
    $this->openParaent=false;
    $this->mother_id=null;
    $this->father_id=$this->familyData['father_id'];
    $this->victim=Victim::find($this->father_id);
    $this->victimForm->fill([
      'Name2'=>$this->victim->Name1,'Name3'=>$this->victim->Name2,'Name4'=>$this->victim->Name4,
      'family_id'=>$this->victim->family_id,'street_id'=>$this->victim->street_id,
      'father_id'=>$this->victim->id,'mother_id'=>$this->victim->wife_id,
      'male'=>'ذكر',
    ]);

    $this->dispatch('gotoitem', test: 'Name1');
  }
  public function InpFather(){
    $this->openName=true;
    $this->openInfo=true;
    $this->openParaent=true;
    $this->victimForm->fill([
      'is_father'=>1,
      'male'=>'ذكر',
    ]);

    $this->dispatch('gotoitem', test: 'Name1');
  }
  public function InpMother(){
    $this->openName=true;
    $this->openInfo=true;
    $this->openParaent=true;
    $this->victimForm->fill([
      'is_mother'=>1,
      'male'=>'أنثي',
    ]);

    $this->dispatch('gotoitem', test: 'Name1');
  }
  public function GoMother(){
    $this->openParaent=false;
    $this->openInfo=false;
    $this->father_id=null;
    $this->mother_id=$this->familyData['mother_id'];
    $this->victim=Victim::find($this->mother_id);
    if ($this->victim->husband_id){
      $hus=Victim::find($this->victim->husband_id);
      $name2=$hus->Name1;$name3=$hus->Name2;$name4=$hus->Name4;
      $this->openName=false;
    } else {$name2='';$name3='';$name4='';$this->openName=true;}
    $this->victimForm->fill([
      'Name2'=>$name2,'Name3'=>$name3,'Name4'=>$name4,
      'family_id'=>$this->victim->family_id,'street_id'=>$this->victim->street_id,
      'father_id'=>$this->victim->husband_id,'mother_id'=>$this->victim->id,
      'male'=>'ذكر',
    ]);

    $this->dispatch('gotoitem', test: 'Name1');
  }

  protected function getFamilyFormSchema(): array
  {
    return [
      Section::make()

       ->schema([
         Actions::make([
           Action::make('father')
             ->label('ادخال أب')
             ->color('blue')
             ->action(function () {$this->InpFather();}),
           Action::make('mother')
             ->label('ادخال أم')
             ->color('Fuchsia')
             ->action(function (){$this->InpMother();}),

         ])->columnSpan(6),

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
           ->afterStateUpdated(fn(Set $set)=>$set('mother_id',null))
           ->extraAttributes([
             'wire:change' => "GoFather",

           ]),
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
           ->afterStateUpdated(fn(Set $set)=>$set('father_id',null))
           ->extraAttributes([
             'wire:change' => "GoMother",

           ]),

       ])->columns(6)
    ];
  }

  public function table(Table $table):Table
  {
    return $table
      ->query(function (Victim $victim)  {

         $victim=Victim::
         when($this->family_id,function ($q){
           $q->where('family_id',$this->family_id) ;
         })
         ->when($this->father_id,function ($q){
           $q->where('father_id',$this->father_id) ;
         })
         ->when($this->mother_id,function ($q){
           $q->where('mother_id',$this->mother_id) ;
          })
        ->when((!$this->mother_id && !$this->father_id && $this->name1  ),function ($q){
          $q->where('Name1','like','%'.$this->name1.'%') ;
        })
        ->when((!$this->mother_id && !$this->father_id && $this->name2  ),function ($q){
          $q->where('Name2','like','%'.$this->name2.'%') ;
        })
        ->when((!$this->mother_id && !$this->father_id && $this->name3  ),function ($q){
          $q->where('Name3','like','%'.$this->name3.'%') ;
        });



        return  $victim;
      })
      ->columns([

        TextColumn::make('FullName')
          ->label('الاسم بالكامل')

      ])
      ->striped()
      ;
  }

}
