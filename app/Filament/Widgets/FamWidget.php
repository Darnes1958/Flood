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
use Illuminate\Support\HtmlString;


class FamWidget extends BaseWidget
{
    protected int | string | array $columnSpan=1;
    protected static ?int $sort=3;
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Family $tribe) {
                $tribe=Family::where('id','!=',null);
                return $tribe;
            }
            )
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب العائلات</div>'))
            ->defaultPaginationPageOption(5)

            ->defaultSort('victims_count','desc')
            ->striped()
            ->columns([
                TextColumn::make('FamName')
                ->sortable()
                ->color('blue')
                ->label('العائلة'),
                TextColumn::make('victims_count')
                 ->counts('victims')

                 ->color('warning')
                 ->sortable()
                 ->label('العدد')
            ]);
    }
}
