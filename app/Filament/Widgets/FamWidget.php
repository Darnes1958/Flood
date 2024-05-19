<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\VictimResource;
use App\Models\Family;
use App\Models\Tribe;
use App\Models\Victim;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;


class FamWidget extends BaseWidget
{
    protected int | string | array $columnSpan=1;
    protected static ?int $sort=3;
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Family $tribe) {
                $tribe=Family::where('nation','ليبيا');
                return $tribe;
            }
            )
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب العائلات</div>'))
            ->defaultPaginationPageOption(5)

            ->defaultSort('victim_count','desc')
            ->striped()
            ->columns([
                TextColumn::make('FamName')
                ->sortable()
                ->color('blue')
                ->searchable()
                ->label('العائلة'),
                TextColumn::make('victim_count')
                  ->color('warning')
                  ->sortable()
                  ->label('العدد')
                 ->counts('Victim'),


            ]);
    }
}
