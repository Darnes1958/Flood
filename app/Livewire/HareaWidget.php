<?php

namespace App\Livewire;

use App\Models\H_area;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

class HareaWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                function (H_area $hadam) {
                    $hadam=H_area::where('id','!=',null);
                    return $hadam;
                }
            )

               ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب محل الإقامة</div>'))
                    ->defaultPaginationPageOption(5)

                    ->defaultSort('hadam_count','desc')
                    ->striped()
                    ->columns([
                        TextColumn::make('name')
                            ->sortable()
                            ->color('blue')
                            ->searchable()
                            ->label('محل الاقامة'),
                        TextColumn::make('hadam_count')
                            ->color('warning')
                            ->sortable()
                            ->label('العدد')
                            ->counts('Hadam'),
            ]);
    }
}
