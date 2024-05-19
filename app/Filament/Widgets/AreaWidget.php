<?php

namespace App\Filament\Widgets;

use App\Models\Area;
use App\Models\Family;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

class AreaWidget extends BaseWidget
{
  protected int | string | array $columnSpan=1;
  protected static ?int $sort=5;

  public function table(Table $table): Table
    {
        return $table
          ->query(function (Area $tribe) {
            $tribe=Area::where('id','!=',null);
            return $tribe;
          }
          )
          ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب المحلة</div>'))
          ->defaultPaginationPageOption(5)

          ->defaultSort('Victim_count','desc')
          ->striped()
          ->columns([
            TextColumn::make('AreaName')
              ->sortable()
              ->color('blue')
              ->searchable()
              ->label('المحلة'),
            TextColumn::make('Victim_count')
              ->color('warning')
              ->sortable()
              ->label('العدد')
              ->counts('Victim'),


          ]);
    }
}
