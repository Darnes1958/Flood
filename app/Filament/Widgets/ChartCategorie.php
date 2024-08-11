<?php

namespace App\Filament\Widgets;

use App\Models\Categorie;
use Filament\Widgets\ChartWidget;

class ChartCategorie extends ChartWidget
{
  protected static ?string $heading = 'المواليد حسب الفئات العمرية';
  protected int | string | array $columnSpan=2;
  protected static ?int $sort=19;
  protected function getData(): array
  {
    $data=$this->getInfo();
    return [
      'datasets' => [
        [
          'label' => 'المواليد',
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
    $res=Categorie::query()->get();
    $theLabels=$res->pluck('name');
    $theData=$res->pluck('count');

    return [
      'theLabels'=> $theLabels,
      'theData' => $theData,
    ];
  }
}
