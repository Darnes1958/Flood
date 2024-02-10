<?php

namespace App\Filament\Pages;

use App\Models\Victim;
use Filament\Pages\Page;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;

class RepGroup extends Page implements HasTable
{
  use \Filament\Tables\Concerns\InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.rep-group';

    protected static ?string $navigationLabel='احصائية';
    protected ?string $heading="";

  public function table(Table $table):Table
  {
    return $table
      ->query(function (Victim $victim)  {
        $victim=Victim::where('Name1','!=',null);
        return  $victim;
      })
      ->columns([

        TextColumn::make('Family.FamName')
          ->summarize(Count::make()->label(''))
          ->label('القبيلة'),
        TextColumn::make('Family.Tribe.TriName')
          ->summarize(Count::make()->label(''))
          ->label('عدد العائلات'),
        TextColumn::make('FullName')
          ->summarize(Count::make()->label(''))
          ->label('الضحايا'),
        TextColumn::make('male')
          ->summarize(
            Count::make()->query(fn (Builder $query) => $query->where('male', 'ذكر'))->label(''),
          )
          ->label('ذكور'),
        TextColumn::make('female')
          ->summarize(
            Count::make()->query(fn (Builder $query) => $query->where('male', 'أنثي'))->label(''),
          )
          ->label('إناث'),
        TextColumn::make('father')
          ->summarize(
            Count::make()->query(fn (Builder $query) => $query->where('father_id', '!=',null))->label(''),
          )
          ->label('أباء'),
        TextColumn::make('mother')
          ->summarize(
            Count::make()->query(fn (Builder $query) => $query->where('mother_id', '!=',null))->label(''),
          )
          ->label('أمهات'),

      ])
      ->groups([

        Group::make('Family.Tribe.TriName')
          ->label('القبيلة')
          ->collapsible(),


      ])
      ->defaultGroup('Family.Tribe.TriName')
      ->groupsOnly()
      ->striped()
      ;
  }
}
