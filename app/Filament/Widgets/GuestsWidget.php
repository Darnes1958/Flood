<?php

namespace App\Filament\Widgets;

use App\Models\Victim;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\TextColumn;

class GuestsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort=11;
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Victim $tribe) {
                $tribe=Victim::where('guests',1);
                return $tribe;
            }
            )
            ->queryStringIdentifier('guests')
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">ضيوف ('.Victim::where('guests',1)->count().')</div>'))
            ->defaultPaginationPageOption(5)
            ->defaultSort('street_id')
            ->striped()
            ->columns([
                TextColumn::make('FullName')
                    ->sortable()
                    ->color('blue')
                    ->searchable()
                    ->label('الاسم'),
                TextColumn::make('notes')
                    ->color('warning')
                    ->sortable()
                    ->label('البيان'),
                ImageColumn::make('image2')
                    ->height(160)
                    ->label('')
                    ->limit(1)
                    ->circular(),

            ]);
    }
}
