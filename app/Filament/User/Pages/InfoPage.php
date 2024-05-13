<?php

namespace App\Filament\User\Pages;

use App\Models\Family;
use App\Models\Victim;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
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
                        ->options(Family::all()->pluck('FamName','id'))
                        ->preload()
                        ->live()
                        ->searchable()
                        ->columnSpan(2)
                        ->afterStateUpdated(function ($state){
                            $this->family_id=$state;
                            $this->mother=Victim::where('family_id',$state)->where('is_mother',1)->pluck('id')->all();
                        }),
                    Radio::make('show')
                     ->inline()
                    ->hiddenLabel()
                        ->reactive()
                    ->live()
                        ->columnSpan(3)
                    ->default('all')
                    ->afterStateUpdated(function ($state){
                        $this->show=$state;
                    })
                    ->options([
                       'all'=>'الكل',
                        'parent'=> 'الوالدين',
                        'single'=>'افراد فقط',
                      'father_only'=>'أباء فقط',
                      'mother_only'=>'أمهات فقط',
                    ])

                ])
                ->columns(5)
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
                    ->when($this->show=='parent',function ($q){
                        $q->where(function ($q){
                            $q->where('is_father',1)
                                ->orwhere('is_mother',1)
                                ;
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
                    ->formatStateUsing(fn (Victim $record): View => view(
                        'filament.user.pages.full-data',
                        ['record' => $record],
                    ))
                    ->searchable(),
                TextColumn::make('Family.FamName')
                    ->label('العائلة')
                    ->visible(function (){
                      return $this->family_id==null ;
                    })
                    ->sortable()

                    ->searchable(),
            ]);
    }
}
