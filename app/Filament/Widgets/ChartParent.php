<?php

namespace App\Filament\Widgets;

use App\Models\Victim;
use Filament\Widgets\ChartWidget;

class ChartParent extends ChartWidget
{
    protected static ?string $heading = 'أباء و أمهات';

  protected static ?int $sort=14;

    protected function getData(): array
    {
      $data=$this->getInfo();
        return [
          'datasets' => [
            [
              'label' => 'Blog posts created',
              'data' => $data['theData'],
              'backgroundColor' => [
                "#483D8B",
                "#FFB6C1",
              ],
            ],
          ],
          'labels' => $data['theLabels'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
  private function getInfo(): array {

    $theLabels=['أباء','أمهات'];
    $theData=[Victim::where('is_father',1)->count(),Victim::where('is_mother',1)->count()];

    return [
      'theLabels'=> $theLabels,
      'theData' => $theData,
    ];
  }
}
