<?php

namespace App\Filament\User\Pages;

use App\Models\Bedon;
use App\Models\Family;
use App\Models\Mafkoden;
use App\Models\Street;
use App\Models\Victim;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class InfoPage extends Page implements HasTable,HasForms
{
    use InteractsWithTable,InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.info-page';
    protected ?string $heading='';
    protected static ?string $navigationLabel='استفسار وبحث';

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
                    ->reactive()
                    ->live()
                    ->columnSpan(2)
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
                    ->reactive()
                    ->live()
                    ->columnSpan(2)
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
                ->columns(4),
              Section::make()
               ->schema([


               ])
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
                    ->description(function (Victim $record){
                      $who='';
                      $bed=null;
                      $maf=null;
                      if ($record->bedon) $bed=Bedon::find($record->bedon);
                      if ($record->mafkoden) $maf=Mafkoden::find($record->mafkoden);
                      if ($bed) {$slash=null; if ($bed->tel) $slash=' / ';
                                 $who= "المبلغ ->   بدون : ".$bed->who.$slash.$bed->tel;}
                      if ($maf)
                        if ($bed) {$slash=null; if ($maf->tel) $slash=' / ';
                                   $who=$who.'   مفقودين : '.$maf->who.$slash.$maf->tel;}
                        else {$slash=null; if ($maf->tel) $slash=' / ';
                              $who=$who.' المبلغ ->   مفقودين : '.$maf->who.$slash.$maf->tel;}

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

            ]);
    }
}