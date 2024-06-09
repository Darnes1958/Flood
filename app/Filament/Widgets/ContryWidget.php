<?php

namespace App\Filament\Widgets;

use App\Models\Area;
use App\Models\Country;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class ContryWidget extends BaseWidget
{
  protected static ?int $sort=8;
  public static function canView(): bool
  {
    return Auth::user()->can('show count');
  }

  public function table(Table $table): Table
    {
        return $table
          ->query(function (Country $tribe) {
            $tribe=Country::where('name','!=',null);
            return $tribe;
          }
          )
          ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب الدولة</div>'))
          ->defaultPaginationPageOption(25)

          ->defaultSort('victim_count','desc')
          ->striped()
            ->columns([
              TextColumn::make('ت')
               ->rowIndex(),
              TextColumn::make('name')
                ->sortable()
                ->color('blue')
                ->searchable()
                ->label('الدولة'),
              TextColumn::make('victim_count')
                ->color('warning')
                ->sortable()
                ->label('العدد')
                ->counts('Victim'),
              Tables\Columns\ImageColumn::make('image')
               ->label(' ')
               ->circular()
            ]);
    }
}
