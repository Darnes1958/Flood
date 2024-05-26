<?php

namespace App\Filament\Widgets;

use App\Models\Family;
use App\Models\Street;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class StreetWidget extends BaseWidget
{
  protected int | string | array $columnSpan=1;
  protected static ?int $sort=4;
    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }

  public function table(Table $table): Table
    {
        return $table
          ->query(function (Street $tribe) {
            $families=Family::where('nation','ليبيا')->pluck('id');
            $tribe=Street::join('victims','street_id','streets.id',)
                ->selectRaw('StrName,count(*) count')
                ->whereIn('family_id',$families)
                ->groupBy('StrName');
            return $tribe;
          }
          )
          ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب العنوان</div>'))
          ->defaultPaginationPageOption(5)

          ->defaultSort('count','desc')
          ->striped()

          ->columns([
            TextColumn::make('StrName')
              ->sortable()
              ->color('blue')
              ->searchable()
              ->label('العنوان'),
            TextColumn::make('count')
              ->color('warning')
              ->sortable()
              ->label('العدد'),
              //->counts('Victim'),


          ]);
    }
}
