<?php

namespace App\Filament\Widgets;

use App\Models\Victim;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class MaleFemale extends BaseWidget
{
    protected int | string | array $columnSpan=2;
    protected static ?int $sort=1;
    protected function getStats(): array
    {
        return [
            Stat::make('','')
                ->label(new HtmlString('<span class="text-white">ذكور</span>'))
                ->value(new HtmlString('<span class="text-primary-500">'.Victim::where('male','ذكر')->count().'</span>')),
            Stat::make('','')
                ->label(new HtmlString('<span class="text-white">إناث</span>'))
                ->value(new HtmlString('<span class="text-danger-600">'.Victim::where('male','أنثي')->count().'</span>')),
            Stat::make('','')
                ->label(new HtmlString('<span class="text-white">أب</span>'))
                ->value(new HtmlString('<span class="text-primary-500">'.Victim::where('is_father',1)->count().'</span>')),
            Stat::make('','')
                ->label(new HtmlString('<span class="text-white">أم</span>'))
                ->value(new HtmlString('<span class="text-danger-600">'.Victim::where('is_mother',1)->count().'</span>')),

        ];
    }
}
