<?php

namespace App\Filament\Widgets;

use App\Models\Road;
use App\Models\Street;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class Buildingwidget extends BaseWidget
{
  protected int | string | array $columnSpan = 1;
    protected static ?int $sort=7;

    public function table(Table $table): Table
    {
        return $table
            ->query(function (Street $tribe) {
                $tribe=Street::where('building',1);
                return $tribe;
            }
            )
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">العمارات</div>'))
            ->defaultPaginationPageOption(5)
            ->queryStringIdentifier('building')
            ->defaultSort('victim_count','desc')
            ->striped()
            ->columns([
                TextColumn::make('StrName')
                    ->sortable()
                    ->color('blue')
                    ->searchable()
                    ->label('العمارة'),
                TextColumn::make('victim_count')
                    ->color('warning')
                    ->sortable()
                    ->label('العدد')
                    ->counts('Victim'),


            ]);
    }
}
