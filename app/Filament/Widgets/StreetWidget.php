<?php

namespace App\Filament\Widgets;

use App\Models\Area;
use App\Models\Family;
use App\Models\Road;
use App\Models\Street;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class StreetWidget extends BaseWidget
{
  protected int | string | array $columnSpan=1;
  protected static ?int $sort=5;
    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }
  public $area_id=null;
  public $road_id=null;
  public $areaName=null;

  #[On('take_road')]
  public function take_road($road_id,$areaName){
    $this->road_id=$road_id;
    $this->areaName=$areaName;
    $this->area_id=null;
  }
  #[On('take_area')]
  public function take_area($area_id,$areaName){
    $this->area_id=$area_id;
    $this->areaName=$areaName;
    $this->road_id=null;
  }
  public function table(Table $table): Table
    {
        return $table
          ->query(function (Street $tribe) {
            $families=Family::where('nation','ليبيا')->pluck('id');
            $tribe=Street::join('victims','street_id','streets.id',)
                ->selectRaw('StrName,count(*) count')
                ->whereIn('family_id',$families)
              ->when($this->road_id,function ($q){
                $q->where('road_id',$this->road_id);
              })

              ->when($this->area_id,function ($q){
                $q->where('area_id',$this->area_id);
              })

                ->groupBy('StrName');
            return $tribe;
          }
          )
            ->queryStringIdentifier('street')
          ->heading(function () {return new HtmlString('<div class="text-primary-400 text-lg ">'.$this->areaName.'</div>');} )
          ->defaultPaginationPageOption(5)

          ->defaultSort('count','desc')
          ->striped()

          ->columns([
            TextColumn::make('StrName')
              ->sortable()
              ->color('blue')
              ->searchable()
              ->label('العنوان'),
            TextColumn::make('count')
              ->color('warning')
              ->sortable()
              ->label('العدد'),
              //->counts('Victim'),


          ]);
    }
}
