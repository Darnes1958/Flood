<?php

namespace App\Filament\Widgets;

use App\Models\Area;
use App\Models\Family;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class AreaWidget extends BaseWidget
{
  protected int | string | array $columnSpan=1;
  protected static ?int $sort=6;
  public static function canView(): bool
  {
    return Auth::user()->can('show count');
  }

  public function table(Table $table): Table
    {
        return $table
          ->query(function (Area $tribe) {
            $tribe=Area::where('id','!=',null);
            return $tribe;
          }
          )
            ->queryStringIdentifier('area')
          ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب المحلة</div>'))
          ->defaultPaginationPageOption(5)

          ->defaultSort('victim_count','desc')
          ->striped()
          ->columns([
            TextColumn::make('AreaName')
              ->sortable()
              ->action(function (Area $record){
                $this->dispatch('take_area',area_id: $record->id,areaName: $record->AreaName);
              })
              ->color('blue')
              ->searchable()
              ->label('المحلة'),
            TextColumn::make('victim_count')
              ->color('warning')
              ->sortable()
              ->label('العدد')
              ->counts('Victim'),


          ]);
    }
}
