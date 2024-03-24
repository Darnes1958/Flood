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


class TriWidget extends BaseWidget
{
    protected int | string | array $columnSpan=1;
    protected static ?int $sort=2;
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Tribe $tribe) {
                $tribe=Tribe::where('id','!=',null);
                return $tribe;
            }
            )
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب القبائل</div>'))
            ->defaultPaginationPageOption(5)

            ->defaultSort('victim_count','desc')
            ->striped()
            ->columns([
                TextColumn::make('TriName')
                ->sortable()
                ->color('blue')
                ->searchable()
                ->label('القبيلة'),
                TextColumn::make('victim_count')
                 ->counts('Victim')

                 ->color('warning')
                 ->sortable()
                 ->label('العدد')
            ]);
    }
}
