<?php

namespace App\Filament\User\Resources\VictimResource\Pages;

use App\Filament\User\Resources\VictimResource;
use App\Models\Victim;

use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\IconColumn;

class ViewEditVictim extends Page implements HasTable
{
  use InteractsWithTable;

    protected static string $resource = VictimResource::class;
    protected ?string $heading='';


    protected static string $view = 'filament.user.resources.victim-resource.pages.view-edit-victim';


  public  function table(Table $table): Table
  {
    return $table
      ->query(Victim::query())
      ->striped()
      ->defaultPaginationPageOption(10)
      ->paginated([10, 25, 50, 100,])
      ->columns([
        TextColumn::make('FullName')
          ->label('الاسم بالكامل')
          ->sortable()
          ->description(function (Victim $record){
            $father='';
            if ($record->father_id)  $father=$record->sonOfFather->FullName;
            if ($record->mother_id)  $father =$father.'/'.$record->sonOfMother->FullName;
            return $father;}
          )
          ->searchable(),
        TextColumn::make('Family.FamName')
          ->sortable()
          ->searchable()
          ->label('العائلة'),
        TextColumn::make('Street.StrName')
          ->sortable()
          ->searchable()
          ->label('العنوان'),

        ImageColumn::make('image')
          ->label('')
          ->circular(),
      ])

      ->filters([
        //


      ])
      ->actions([
        //

      ]);
  }
}
