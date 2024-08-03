<?php

namespace App\Filament\Widgets;

use App\Models\Country;
use App\Models\Family;
use App\Models\Victim;
use Filament\Widgets\ChartWidget;

class ChartNation extends ChartWidget
{
    protected static ?string $heading = 'الجنسيات';
    protected int | string | array $columnSpan=2;
  protected static ?int $sort=12;
    protected function getData(): array
    {
      $data=$this->getInfo();
      return [
        'datasets' => [
          [
            'label' => 'الجنسيات',
            'data' => $data['theData'],
            'backgroundColor' => [
              "#483D8B",
              "#FFB6C1",
              "#7FFF00",
              "#0000FF",
              "#DEB887",
              "#006400",
              "#8B0000",
              "#FF8C00",
              '#483D8B',
              '#8B008B',
              '#2F4F4F',
              '#00CED1',
              '#FFD700',

            ],
          ],
        ],
        'labels' => $data['theLabels'],
      ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
  private function getInfo(): array {
      $res=Country::withCount('Victim')->get();
    $theLabels=$res->pluck('name');
    $theData=$res->pluck('victim_count');

    return [
      'theLabels'=> $theLabels,
      'theData' => $theData,
    ];
  }
}
