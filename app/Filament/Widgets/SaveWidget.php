<?php

namespace App\Filament\Widgets;

use App\Models\Victim;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class SaveWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort=12;

    public function table(Table $table): Table
    {
        return $table
            ->query(function (Victim $tribe) {
                $tribe=Victim::where('inSave',1);
                return $tribe;
            }
            )
            ->queryStringIdentifier('inSave')
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">منقذين ('.Victim::where('inSave',1)->count().')</div>'))
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
            ]);
    }
}
