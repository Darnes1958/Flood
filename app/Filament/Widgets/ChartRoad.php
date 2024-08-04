<?php

namespace App\Filament\Widgets;

use App\Models\Road;
use App\Models\Victim;
use Filament\Widgets\ChartWidget;

class ChartRoad extends ChartWidget
{

  protected static ?int $sort=12;
    protected static ?string $heading = 'الذكور والإناث';

  protected function getData(): array
  {
    $data=$this->getRaods();
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
    private function getRaods(): array {
      $theLabels=[];
      $thaData=[];
     // $theLabels=Road::withCount('Victim')->pluck('name');
     // $theData=Road::withCount('Victim')->pluck('victim_count');
      $theLabels=['ذكور','اناث'];
      $theData=[Victim::where('male','ذكر')->count(),Victim::where('male','أنثي')->count()];

      return [
        'theLabels'=> $theLabels,
        'theData' => $theData,
        ];
    }
}
