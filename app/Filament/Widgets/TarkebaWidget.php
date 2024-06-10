<?php

namespace App\Filament\Widgets;

use App\Models\Family;
use App\Models\Tarkeba;
use App\Models\Tribe;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class TarkebaWidget extends BaseWidget
{
  protected static ?int $sort=9;
  public static function canView(): bool
  {
    return Auth::user()->can('show count');
  }
  public function table(Table $table): Table
  {
    return $table
      ->query(function (Tarkeba $tribe) {
        $tribe=Tarkeba::where('id','!=',null);
        return $tribe;
      }
      )
      ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب التركيبة الاجتماعية</div>'))
      ->defaultPaginationPageOption(5)

      ->defaultSort('victim_sum_count','desc')
      ->striped()
      ->columns([
        TextColumn::make('name')
          ->sortable()
          ->color('blue')
          ->searchable()
          ->label('القبيلة'),
        TextColumn::make('victim_sum_count')
          ->sum('Victim','Count')

          ->color('warning')
          ->sortable()
          ->label('العدد')
      ]);
  }
}
