<?php

namespace App\Livewire;

use App\Models\Hadam;
use App\Models\Place_type;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

class PlaceTypeWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                function (Place_type $hadam) {
                    $hadam=Place_type::where('id','!=',null);
                   return $hadam;
                }
            )
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب نوع العقار</div>'))
            ->defaultPaginationPageOption(5)

            ->defaultSort('hadam_count','desc')
            ->striped()
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->color('blue')
                    ->searchable()
                    ->label('نوع العقار'),
                TextColumn::make('hadam_count')
                    ->color('warning')
                    ->sortable()
                    ->label('العدد')
                    ->counts('Hadam'),

            ]);
    }
}
