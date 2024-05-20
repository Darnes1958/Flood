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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;


class TriWidget extends BaseWidget
{
    protected int | string | array $columnSpan=1;
    protected static ?int $sort=2;
  public static function canView(): bool
  {
    return Auth::user()->can('show count');
  }
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Tribe $tribe) {
                $family=Family::where('nation','ليبيا')->pluck('tribe_id');
                $tribe=Tribe::wherein('id',$family);
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
