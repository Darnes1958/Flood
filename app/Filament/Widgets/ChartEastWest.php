<?php

namespace App\Filament\Widgets;

use App\Models\Road;
use App\Models\Street;
use App\Models\Victim;
use Filament\Widgets\ChartWidget;

class ChartEastWest extends ChartWidget
{
    protected static ?string $heading = 'مقارن بين غرب الوادي وشرقه';
  protected static ?int $sort=15;
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
    $west=Street::whereIn('road_id',Road::where('east_west','غرب الوادي')->pluck('id'))->pluck('id');
    $east=Street::whereIn('road_id',Road::where('east_west','شرق الوادي')->pluck('id'))->pluck('id');

    $theLabels=['شرق الوادي','غرب الوادي'];
    $theData=[
      Victim::whereIn('street_id',$east)->count(),
      Victim::whereIn('street_id',$west)->count(),
    ];

    return [
      'theLabels'=> $theLabels,
      'theData' => $theData,
    ];
  }
}
