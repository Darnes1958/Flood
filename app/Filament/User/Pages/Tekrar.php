<?php

namespace App\Filament\User\Pages;

use App\Models\Allview;
use App\Models\Bedmafview;
use App\Models\Tasbedview;
use App\Models\Tasmafview;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tekrar extends Page implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.tekrar';
    protected static ?string $navigationLabel='التداخل بين الملفات';

    protected ?string $heading='';
    protected static ?int $navigationSort=3;

    public $what='inTasAndBed';

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
                    'inTasAndBed'=>'بتصريح وبدون تصريح',
                    'inTasAndMaf'=>'بتصريح ومفقودين',
                    'inBedAndMaf'=>'بدون تصريح ومفقودين',
                    'inAll'=>'في الثلاث ملفات',
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
                            return route('pdftekrar', ['what' => $this->what]);
                        } ),

                ]),


            ]);
    }
    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }
    public function table(Table $table): Table
    {
        return $table

            ->striped()
            ->defaultSort(function (){
               return 'nameTas';

            })
            ->query(function (){
                if ($this->what=='inTasAndBed')  return $victim = Tasbedview::query()->orderBy('nameTas');
                if ($this->what=='inTasAndMaf')  return $victim = Tasmafview::query()->orderBy('nameTas');
                if ($this->what=='inBedAndMaf')  return $victim = Bedmafview::query()->orderBy('nameBed');
                if ($this->what=='inAll')  return $victim = Allview::query()->orderBy('nameTas');

            })
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label('ت'),
                TextColumn::make('FamName')
                   ->sortable()
                   ->searchable()
                   ->label('العائلة'),
                TextColumn::make('nameTas')
                    ->sortable()
                    ->searchable()
                    ->color('success')
                    ->visible(function (){
                       return $this->what=='inTasAndBed' || $this->what=='inTasAndMaf' || $this->what=='inAll';
                    })
                    ->label('بتصريح'),
                TextColumn::make('nameBed')
                    ->sortable()
                    ->searchable()
                    ->color('primary')
                    ->visible(function (){
                        return $this->what=='inTasAndBed' || $this->what=='inBedAndMaf' || $this->what=='inAll';
                    })
                    ->label('بدون'),
                TextColumn::make('nameMaf')
                    ->sortable()
                    ->searchable()
                    ->color('rose')
                    ->visible(function (){
                        return $this->what=='inTasAndMaf' || $this->what=='inBedAndMaf' || $this->what=='inAll';
                    })
                    ->label('مفقودين'),

            ]);
    }
}
