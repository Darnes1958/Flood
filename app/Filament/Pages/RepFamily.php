<?php

namespace App\Filament\Pages;

use App\Models\Victim;
use Filament\Pages\Page;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class RepFamily extends Page implements HasTable
{
  use \Filament\Tables\Concerns\InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.rep-family';

  protected static ?string $navigationLabel='تقرير بالعائلات';
  protected ?string $heading="";

  public function table(Table $table):Table
  {
    return $table
      ->query(function (Victim $victim)  {
        $victim=Victim::where('is_father','!=',null)
          ->orwhere('is_mother','!=',null)
        ->orwhere([
          ['father_id',null],
          ['mother_id',null],
          ['is_father',null],
          ['is_mother',null],
        ]);
        return  $victim;
      })
      ->columns([

        TextColumn::make('FullName')
          ->label('الاسم بالكامل')
          ->color(function (Victim $record) {
            if ($record->is_father) return 'blue';
            if ($record->is_mother) return 'Fuchsia';
          })

          ->description(function(Victim $record) {
            if ($record->is_father)
            {
              $vic=Victim::where('father_id',$record->id)->get();
              $Arr='وأبناءه : ';
              foreach ($vic as $v) if ($Arr=='وأبناءه : ') $Arr=$Arr.$v->Name1; else $Arr=$Arr.','.$v->Name1;
              return ($Arr);
            }
            if ($record->is_mother)
            {
              $vic=Victim::where('wife_id',$record->id)->first();
              if ($vic) return 'زوجة : '.$vic->FullName;
              else return (null);
            }
          }

          )

          ->searchable(),
        TextColumn::make('Street.StrName')
          ->label('الشارع'),
        TextColumn::make('Street.Area.AreaName')
          ->label('المحلة'),

        TextColumn::make('Family.Tribe.TriName')
          ->label('القبيلة'),
      ])
      ->groups([
        Group::make('Family.FamName')
          ->label('عائلة')
          ->getTitleFromRecordUsing(fn (Victim $record): string =>
          ($record->Family->FamName.' '. Victim::where('family_id',$record->family_id)->count()))
          ->collapsible(),
      ])
      ->defaultGroup('Family.FamName')
      ->striped();
  }
}
