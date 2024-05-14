<?php

namespace App\Filament\User\Pages;

use App\Models\Bedon;
use App\Models\Family;
use App\Models\Mafkoden;
use App\Models\Street;
use App\Models\Tasreeh;
use App\Models\Victim;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
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



    public function form(Form $form): Form
    {
        return $form
            ->schema([
               Section::make()
                ->schema([
                  Select::make('family_id')
                      ->hiddenLabel()
                      ->prefix('العائلة')
                      ->options(Family::all()->pluck('FamName','id'))
                      ->preload()
                      ->live()
                      ->searchable()
                      ->columnSpan(3)
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

                  \Filament\Forms\Components\Actions::make([
                    \Filament\Forms\Components\Actions\Action::make('SerWho')
                      ->label('بحث عن المبلغين')
                      ->badge()
                      ->icon('heroicon-s-magnifying-glass')
                      ->color('success')
                      ->modalContent(view('filament.user.pages.who-search-widget'))
                      ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة')->icon('heroicon-s-arrow-uturn-left'))
                      ->modalSubmitAction(false)

                    ,
                  ])->columnSpan(2)->alignCenter()->verticallyAlignCenter(),
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
                    })
            ]);
    }
}
