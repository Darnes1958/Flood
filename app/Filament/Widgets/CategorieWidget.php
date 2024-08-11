<?php

namespace App\Filament\Widgets;

use App\Models\Categorie;
use App\Models\Year;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class CategorieWidget extends BaseWidget
{
  protected static ?int $sort=14;
  public function getTableRecordKey(Model $record): string
  {
    return uniqid();
  }
  public function table(Table $table): Table
  {
    return $table
      ->query(
        function (Categorie $hadam) {
          $hadam=Categorie::query();
          return $hadam;
        }
      )

      ->heading(new HtmlString('<div class="text-primary-400 text-lg">المواليد حسب الفئات العمرية</div>'))
      ->defaultPaginationPageOption(5)
      ->queryStringIdentifier('Categories')
      ->defaultSort('no')
      ->striped()
      ->columns([
        TextColumn::make('name')
          ->sortable()
          ->color('blue')
          ->searchable()
          ->label('الفترة'),
        TextColumn::make('count')
          ->color('warning')
          ->sortable()
          ->label('العدد')
        ,
      ]);
  }
}
