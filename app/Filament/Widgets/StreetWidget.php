<?php

namespace App\Filament\Widgets;

use App\Models\Family;
use App\Models\Street;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

class StreetWidget extends BaseWidget
{
  protected int | string | array $columnSpan=1;
  protected static ?int $sort=4;

  public function table(Table $table): Table
    {
        return $table
          ->query(function (Street $tribe) {
            $tribe=Street::where('id','!=',null);
            return $tribe;
          }
          )
          ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب العنوان</div>'))
          ->defaultPaginationPageOption(5)

          ->defaultSort('Victim_count','desc')
          ->striped()
          ->columns([
            TextColumn::make('StrName')
              ->sortable()
              ->color('blue')
              ->searchable()
              ->label('العنوان'),
            TextColumn::make('victim_count')
              ->color('warning')
              ->sortable()
              ->label('العدد')
              ->counts('Victim'),


          ]);
    }
}
