<?php

namespace App\Filament\Widgets;

use App\Models\Area;
use App\Models\Road;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class Roadwidget extends BaseWidget
{
    protected static ?int $sort=6;
    public static function canView(): bool
    {
        return Auth::user()->can('show count');
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Road $tribe) {
                $tribe=Road::where('id','!=',null);
                return $tribe;
            }
            )
            ->heading(new HtmlString('<div class="text-primary-400 text-lg">العدد حسب الشوارع الرئيسية</div>'))
            ->defaultPaginationPageOption(5)

            ->defaultSort('victim_count','desc')
            ->striped()
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->color('blue')
                    ->searchable()
                    ->label('الشارع'),
                TextColumn::make('victim_count')
                    ->color('warning')
                    ->sortable()
                    ->label('العدد')
                    ->counts('Victim'),


            ]);
    }
}
