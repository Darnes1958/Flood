<?php

namespace App\Filament\User\Pages;

use App\Models\Allview;
use App\Models\Bedmafview;
use App\Models\Bedon;
use App\Models\Mafkoden;
use App\Models\Tasbedview;
use App\Models\Tasmafview;
use App\Models\Tasreeh;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class Repeted extends Page implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.repeted';
    protected static ?string $navigationLabel='التكرار';
    protected ?string $heading='';

    public $what='inTas';

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
                        'inTas'=>'بتصريح',
                        'inMaf'=>'مفقودين',
                        'inBed'=>'بدون تصريح',
                    ])
                    ->inline()
                    ->afterStateUpdated(function ($state){
                        return $this->what=$state;
                    })
                    ->live(),
                \Filament\Forms\Components\Actions::make([
                    Action::make('printTekrara')
                        ->label('طباعة')

                        ->icon('heroicon-m-printer')
                        ->url(function () {
                            return route('pdfrepeted', ['what' => $this->what]);
                        } ),

                ]),


            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort(function (){
                return 'name';
            })
            ->query(function (){
                if ($this->what=='inTas')  return $victim = Tasreeh::query()->where('repeted',1)->orderBy('name');
                if ($this->what=='inBed')  return $victim = Bedon::query()->where('repeted',1)->orderBy('name');
                if ($this->what=='inMaf')  return $victim = Mafkoden::query()->where('repeted',1)->orderBy('name');
            })
            ->columns([
                TextColumn::make('ser')
                    ->rowIndex()
                    ->label('ت'),
                TextColumn::make('Family.FamName')
                    ->sortable()
                    ->searchable()
                    ->label('العائلة'),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('الاسم'),

            ]);
    }
}
