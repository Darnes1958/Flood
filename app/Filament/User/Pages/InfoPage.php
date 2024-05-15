<?php

namespace App\Filament\User\Pages;

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
use Filament\Support\Enums\ActionSize;
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

class InfoPage extends Page implements HasTable,HasForms
{
    use InteractsWithTable,InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.info-page';
    protected ?string $heading='';
    protected static ?string $navigationLabel='استفسار وبحث';
    protected static ?int $navigationSort=1;

    public $family_id=null;
    public $street_id=null;
    public $show='all';
    public $mother;
    public $count;
    public $from='all';
    public $ok=1;

  public function  mount() {
    $this->form->fill(['ok'=>1,]);
  }
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
                          ->where('ok','!=', $this->ok)
                          ->pluck('FamName', 'id'))
                      ->preload()
                      ->live()
                      ->searchable()
                      ->columnSpan(3)
                      ->afterStateUpdated(function ($state){
                          $this->family_id=$state;
                          $this->mother=Victim::where('family_id',$state)->where('is_mother',1)->pluck('id')->all();
                      }),
                  Checkbox::make('ok')
                   ->live()
                   ->default(1)
                   ->afterStateUpdated(function ($state){
                     $this->ok=$state;
                   })
                   ->label('لم تراجع'),
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

                  \Filament\Forms\Components\Actions::make([
                    \Filament\Forms\Components\Actions\Action::make('SerWho')
                      ->label('بحث عن المبلغين')
                      ->badge()
                      ->icon('heroicon-s-magnifying-glass')
                      ->color('success')
                      ->modalContent(view('filament.user.pages.who-search-widget'))
                      ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة')->icon('heroicon-s-arrow-uturn-left'))
                      ->modalSubmitAction(false),
                  ])->alignCenter()->verticallyAlignCenter(),
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
                  Radio::make('from')
                    ->inline()
                    ->hiddenLabel()
                    ->inlineLabel(false)
                    ->reactive()
                    ->live()
                    ->columnSpan(4)
                    ->default('all')
                    ->afterStateUpdated(function ($state){
                      $this->from=$state;
                    })
                    ->options([
                      'all'=>'الكل',
                      'tasreeh'=>'بتصريح',
                      'bedon'=>'بدون',
                      'mafkoden'=>'مفقودين',
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
                  ->when($this->from=='bedon'  ,function ($q){
                    $q->where('fromwho','بدون');
                  })
                  ->when($this->from=='tasreeh',function ($q){
                    $q->where('fromwho','بتصريح');
                  })
                  ->when($this->from=='mafkoden',function ($q){
                    $q->where('fromwho','مفقودين');
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
              TextColumn::make('fromwho')
                ->color(function ($state){
                  switch ($state){
                    case 'المنظومة': $c='info';break;
                    case 'مفقودين': $c='rose';break;
                    case 'بتصريح': $c='success';break;
                    case 'بدون': $c='primary';break;
                  }
                  return $c;
                })
                ->toggleable()
                ->label('بواسطة'),
                TextColumn::make('FullName')
                    ->label('الاسم بالكامل')

                    ->sortable()
                    ->searchable()
                    ->action(
                        Action::make('updateName')
                            ->form([
                                TextInput::make('Name1')
                                    ->label('الإسم الاول')
                                    ->required(),
                                TextInput::make('Name2')
                                    ->label('الإسم الثاني')
                                    ->required(),
                                TextInput::make('Name3')
                                    ->label('الإسم الثالث'),
                                TextInput::make('Name4')
                                    ->label('الإسم الرابع'),
                              TextInput::make('otherName')
                                ->label('إسم أخر'),
                                ])
                            ->fillForm(fn (Victim $record): array => [
                                'Name1' => $record->Name1,'Name2' => $record->Name2,'Name3' => $record->Name3,'Name4' => $record->Name4
                            ])
                            ->modalCancelActionLabel('عودة')
                            ->modalSubmitActionLabel('تحزين')
                            ->modalHeading('تعديل الإسم')
                            ->action(function (array $data,Victim $record,){
                                $record->update(['Name1'=>$data['Name1'],'Name2'=>$data['Name2'],'Name3'=>$data['Name3'],'Name4'=>$data['Name4'],
                                    'FullName'=>$data['Name1'].' '.$data['Name2'].' '.$data['Name3'].' '.$data['Name4'], ]);
                            })
                    )
                    ->description(function (Victim $record){
                      $who='';
                      $bed=null;
                      $maf=null;
                      if ($record->bedon) $bed=Bedon::find($record->bedon);
                      if ($record->mafkoden) $maf=Mafkoden::find($record->mafkoden);
                      if ($bed) {$slash=null; if ($bed->tel) $slash=' / ';
                                 $who= "المبلغ ->   بدون : ".$bed->who.$slash.$bed->tel; if ($bed->ship) $who=$who.' ('.$bed->ship.') ';}
                      if ($maf)
                        if ($bed) {$slash=null; if ($maf->tel) $slash=' / ';
                                   $who=$who.'   مفقودين : '.$maf->who.$slash.$maf->tel;}
                        else {$slash=null; if ($maf->tel) $slash=' / ';
                              $who=$who.' المبلغ ->   مفقودين : '.$maf->who.$slash.$maf->tel;}

                       if ($bed || $maf) {
                           $who=$who.' الأم -> ';
                           if ($bed && $bed->mother) $who=$who.'بدون : '.$bed->mother;
                           if ($maf && $maf->mother) $who=$who.'مفقودين : '.$maf->mother;
                       }


                        return $who;

                    })
                    ->formatStateUsing(fn (Victim $record): View => view(
                        'filament.user.pages.full-data',
                        ['record' => $record],
                    ))
                    ->searchable(),
                TextColumn::make('Family.FamName')
                    ->label('العائلة')
                    ->action(
                    Action::make('updateFamily')
                      ->form([
                        Select::make('family_id')
                          ->options(Family::all()->pluck('FamName','id'))
                          ->label('العائلة')
                          ->searchable()
                          ->preload()
                          ->live()
                      ])
                      ->fillForm(fn (Victim $record): array => [
                        'family_id' => $record->family_id,
                      ])
                      ->modalCancelActionLabel('عودة')
                      ->modalSubmitActionLabel('تحزين')
                      ->modalHeading('تعديل العائلة')
                      ->action(function (array $data,Victim $record,){
                        $record->update(['family_id'=>$data['family_id']]);
                      })
                  )
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
              TextColumn::make('Street.StrName')
                ->label('العنوان')
                ->action(
                  Action::make('updateٍStreet')
                    ->form([
                      Select::make('street_id')
                        ->options(Street::all()->pluck('StrName','id'))
                        ->label('العنوان')
                        ->searchable()
                        ->preload()
                        ->live()
                    ])
                    ->fillForm(fn (Victim $record): array => [
                      'street_id' => $record->street_id,
                    ])
                    ->modalCancelActionLabel('عودة')
                    ->modalSubmitActionLabel('تحزين')
                    ->modalHeading('تعديل العنوان')
                    ->action(function (array $data,Victim $record,){
                      $record->update(['street_id'=>$data['street_id']]);
                    })
                )
                ->toggleable()
                ->sortable()
                ->searchable(),
               IconColumn::make('is_mother')
                    ->label('أم')
                    ->color(function ($state){
                        if ($state) return 'Fuchsia'; else return 'yellow';
                    })
                    ->action(
                        Action::make('ismother')
                            ->action(function (Victim $record,){
                                if ($record->is_mother)
                                 $record->update(['is_mother'=>false]);
                                else {
                                 if ($record->male=='أنثي') $record->update(['is_mother'=>true]);
                                }

                            })
                    )
                    ->boolean(),
                IconColumn::make('is_father')
                    ->label('أب')
                    ->color(function ($state){
                        if ($state) return 'success'; else return 'gray';
                    })
                    ->action(
                        Action::make('isfather')
                            ->action(function (Victim $record,){
                                if ($record->is_father)
                                    $record->update(['is_father'=>false]);
                                else {
                                    if ($record->male=='ذكر') $record->update(['is_father'=>true]);
                                }
                            })
                    )
                    ->boolean(),

            ])
            ->actions([
              Action::make('edit')
               ->iconButton()
                ->color('info')
               ->icon('heroicon-s-pencil')
                ->url(fn (Victim $record): string => route('filament.user.resources.victims.edit', ['record' => $record]))
               ,
              Action::make('delete')
               ->iconButton()
               ->visible(Auth::user()->can('delete victim'))
               ->icon('heroicon-s-trash')
               ->requiresConfirmation()
               ->action(function (Victim $record){
                 $record->delete();
               }),
                Action::make('RetTasreeh')
                    ->label('ارجاع')
                    ->requiresConfirmation()
                    ->modalSubmitActionLabel('نعم')
                    ->modalCancelActionLabel('لا')
                    ->fillForm(fn (Victim $record): array => [
                        'family_id' => $record->family_id,
                         'id' => $record->id,
                    ])
                    ->form([
                      TextInput::make('family_id')
                        ->label('كود العائلة')
                        ->hidden()
                        ->live()
                        ->readOnly(),
                      TextInput::make('id')
                        ->label('id')
                        ->hidden()
                        ->live()
                        ->readOnly(),
                      Select::make('victim_id')
                        ->label('فالمنظومة')
                        ->searchable()
                        ->autofocus()
                        ->preload()
                        ->required()
                        ->options(fn (Get $get): Collection => Victim::query()
                          ->where('family_id', $get('family_id'))
                          ->where('id','!=',$get('id'))
                          ->pluck('FullName', 'id'))
                    ])
                    ->visible(fn(Victim $record)=>$record->fromwho!='المنظومة')
                    ->action(function (array $data,Victim $record): void {
                        if ($record->fromwho=='بتصريح')
                        {Tasreeh::find($record->tasreeh)->update(['victim_id'=>$data['victim_id']]);
                            Victim::find($data['victim_id'])->update(['tasreeh'=>$record->tasreeh]);}
                        if ($record->fromwho=='بدون')
                        {Bedon::find($record->bedon)->update(['victim_id'=>$data['victim_id']]);
                            Victim::find($data['victim_id'])->update(['bedon'=>$record->bedon]);}
                        if ($record->fromwho=='مفقودين')
                        {Mafkoden::find($record->mafkoden)->update(['victim_id'=>$data['victim_id']]);
                            Victim::find($data['victim_id'])->update(['mafkoden'=>$record->mafkoden]);}


                        $record->delete();
                    }),
                Action::make('Updparent')
                    ->label('ربط')
                    ->modalWidth(MaxWidth::ThreeExtraLarge)

                    ->modalSubmitActionLabel('نعم')
                    ->modalCancelActionLabel('لا')
                    ->fillForm(fn (Victim $record): array => [
                        'family_id' => $record->family_id,
                        'male' => $record->male,
                        'is_mother' => $record->is_mother,
                        'is_father' => $record->is_father,
                        'mother_id' => $record->mother_id,
                        'father_id' => $record->father_id,
                        'husband_id' => $record->husband_id,
                        'wife_id' => $record->wife_id,
                    ])
                    ->form([
                        Section::make()
                         ->schema([
                             TextInput::make('family_id')
                              ->hidden()
                              ->readOnly(),

                             Radio::make('male')
                                 ->label('الجنس')
                                 ->inline()
                                 ->default('ذكر')
                                 ->reactive()
                                 ->afterStateUpdated(function(Set $set,$state) {
                                     if ($state=='ذكر')  $set('is_mother',0);
                                     else $set('is_father',0);})
                                 ->options([
                                     'ذكر' => 'ذكر',
                                     'أنثي' => 'أنثى',
                                 ]),
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
                                 ->label('أم'),
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
                                 ->options(fn (Get $get): Collection => Victim::query()
                                     ->where('family_id', $get('family_id'))
                                     ->where('is_father',1)
                                     ->where('id','!=',$get('id'))
                                     ->pluck('FullName', 'id'))
                                 ->searchable()
                                 ->live()
                                 ->afterStateUpdated(function ($state,Set $set){
                                     $set('mother_id',Victim::find($state)->wife_id);
                                 })
                                 ->preload(),
                             Select::make('mother_id')
                                 ->label('والدته')
                                 ->relationship('sonOfMother','FullName', fn (Builder $query) => $query
                                     ->where('male','أنثي'))
                                 ->searchable()
                                 ->reactive()
                                 ->preload(),
                         ])
                            ->columns(3)
                            ->columnSpanFull()

                    ])

                    ->action(function (array $data,Victim $record): void {
                        if ($data['male']=='ذكر') $data = Arr::add($data,'husband_id', null);
                        if ($data['male']=='أنثي') $data = Arr::add($data,'wife_id', null);
                        $record->update(['mother_id'=>$data['mother_id'],'father_id'=>$data['father_id'],'husband_id'=>$data['husband_id'],
                            'wife_id'=>$data['wife_id'],'male'=>$data['male'],]);
                    }),
            ]);
    }
}
