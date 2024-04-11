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
            ->label(new HtmlString('<span class="text-white">العدد الكلي</span>'))
            ->value(new HtmlString('<span class="text-primary-500">'.Victim::count().'</span>')),
          Stat::make('','')
            ->label(new HtmlString('<span class="text-white">ليبيين</span>'))
            ->value(new HtmlString('<span class="text-primary-500">'.Victim::whereNotin('family_id',[120,162,207,250,303,306,308,343,344,345,346,347])->count().'</span>')),
          Stat::make('','')
            ->label(new HtmlString('<span class="text-white">أجانب</span>'))
            ->value(new HtmlString('<span class="text-primary-500">'.Victim::whereIn('family_id',[120,162,207,250,303,306,308,343,344,345,346,347])->count().'</span>')),


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
