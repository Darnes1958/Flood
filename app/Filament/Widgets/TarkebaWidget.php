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
  protected int | string | array $columnSpan = 1;
  protected static ?int $sort=1;

  public function table(Table $table): Table
  {
    return $table
      ->query(function (Tarkeba $tribe) {
        $tribe=Tarkeba::where('id','!=',null);
        return $tribe;
      }
      )
        ->queryStringIdentifier('tarkeba')
      ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب التركيبة الاجتماعية</div>'))
      ->defaultPaginationPageOption(5)

      ->defaultSort('victim_sum_count','desc')
      ->striped()
      ->columns([
        TextColumn::make('name')
          ->sortable()
          ->action(function (Tarkeba $record){
             $this->dispatch('take_tarkeba',tarkeba_id: $record->id);
          })
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
