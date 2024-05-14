<?php

namespace App\Filament\User\Resources\VictimResource\Pages;

use App\Filament\User\Pages\Mysearch;
use App\Filament\User\Resources\VictimResource;
use Filament\Actions;

use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListVictims extends ListRecords
{
    protected static string $resource = VictimResource::class;
    protected ?string $heading=' ';
    protected static ?int $navigationSort =2;

  public $family_id;



  public function getTabs(): array
  {
    return [
      'all' => Tab::make('الجميع')
      ,
      'mysystem' => Tab::make('المنظومة')
        ->modifyQueryUsing(fn (Builder $query) => $query->where('fromwho', 'المنظومة')),
      'tasreeh' => Tab::make('بتصريح')
        ->modifyQueryUsing(fn (Builder $query) => $query->where('fromwho', 'بتصريح')),
      'bedon' => Tab::make('بدون')
        ->modifyQueryUsing(fn (Builder $query) => $query->where('fromwho', 'بدون')),
      'mafkoden' => Tab::make('مفقودين')
        ->modifyQueryUsing(fn (Builder $query) => $query->where('fromwho', 'مفقودين')),
      'sysOnly' => Tab::make(' في المنظومة فقط')
        ->modifyQueryUsing(fn (Builder $query) => $query->where('bedon', null)
          ->where('mafkoden', null)->where('tasreeh', null)),
      'other' => Tab::make('غير موجود بالمنظومة')
        ->modifyQueryUsing(fn (Builder $query) => $query->where('fromwho','!=', 'المنظومة')),
    ];
  }

}
