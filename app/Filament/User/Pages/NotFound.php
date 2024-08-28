<?php

namespace App\Filament\User\Pages;

use App\Models\Family;
use App\Models\Victim;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class NotFound extends Page implements HasTable,HasForms
{
    use InteractsWithTable,InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.not-found';
    protected static ?string $navigationLabel='غير موجودين بسجلات النيابة';
    protected ?string $heading='';

    public $what='newData';
    public $libya=true;
    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->is_admin;
    }
    public function  mount() {
        $this->form->fill([]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Radio::make('what')
                    ->hiddenLabel()
                    ->options([
                        'newData'=>'البلاعات الجديدة',
                        'oldData'=>'البلاعات القديمة',
                        'oldNotnewData'=>'مبلغ في القديمة وغير مبلغ غالجديدة',
                        'allData'=>'الكل',

                    ])
                    ->inline()
                    ->afterStateUpdated(function ($state){
                        return $this->what=$state;
                    })
                    ->live()
                    ->columnSpan(2),
                Checkbox::make('libya')
                 ->label('ليبين فقط')
                    ->live()
                 ->afterStateUpdated(function ($state){
                     return $this->libya=$state;
                 }),
                \Filament\Forms\Components\Actions::make([
                    Action::make('print1')
                        ->label('طباعة')

                        ->icon('heroicon-m-printer')
                        ->url(function () {
                            return route('pdfnewold', ['what' => $this->what,'libya'=>$this->libya]);
                        } ),

                ]),


            ])->columns(2);
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                if ($this->what=='newData')
                    return Victim::where('balag',null)->where('dead',null)
                        ->when($this->libya,function ($q){
                            $q->wherein('family_id',Family::where('nation','ليبيا')->pluck('id'));
                        });
                if ($this->what=='oldData')
                    return Victim::where('tasreeh',null)->where('bedon',null)->where('mafkoden',null)
                        ->when($this->libya,function ($q){
                            $q->wherein('family_id',Family::where('nation','ليبيا')->pluck('id'));
                        });

                if ($this->what=='oldNotnewData') return Victim::
                    where(function ($q){
                        $q->where('tasreeh','!=',null)->orwhere('bedon','!=',null)
                            ->orwhere('mafkoden','!=',null);
                      })
                    ->where('balag',null)->where('dead',null)
                    ->when($this->libya,function ($q){
                        $q->wherein('family_id',Family::where('nation','ليبيا')->pluck('id'));
                    });


                if ($this->what=='allData') return Victim::
                 where('tasreeh',null)->where('bedon',null)->where('mafkoden',null)
                    ->where('balag',null)->where('dead',null)
                    ->when($this->libya,function ($q){
                        $q->wherein('family_id',Family::where('nation','ليبيا')->pluck('id'));
                    });

            }
            )
            ->columns([
                TextColumn::make('Family.FamName')
                    ->sortable()
                    ->label('العائلة'),
                TextColumn::make('FullName')
                    ->sortable()
                    ->searchable()
                    ->label('الاسم'),
                    ]
            );
    }

}
