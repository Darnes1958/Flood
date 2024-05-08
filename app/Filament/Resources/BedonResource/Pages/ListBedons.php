<?php

namespace App\Filament\Resources\BedonResource\Pages;

use App\Filament\Resources\BedonResource;
use App\Models\Bedon;
use App\Models\Family;
use App\Models\Mafkoden;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBedons extends ListRecords
{
    protected static string $resource = BedonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة'),
          Actions\Action::make('Modifybedon')

            ->label('تعديلات ')
            ->icon('heroicon-m-users')
            ->color('danger')
            ->url('bedons/modifybedon'),
          Actions\Action::make('setfamily')
            ->label('set family')
            ->visible(false)
            ->action(function (){
              $fam=Family::all();
              foreach ($fam as $item){
                  if ($item->FamName=='اسماعيل') continue;
                  if ($item->FamName=='عزوز') continue;
                  if ($item->FamName=='بدر') continue;
                  if ($item->FamName=='نو') continue;
                  if ($item->FamName=='غفير') continue;
                  if ($item->FamName=='رافع') continue;
                Bedon::
                where('name','like','%'.$item->FamName.'%')
                    ->where('nation','ليبيا')
                    ->update(['family_id'=>$item->id]) ;

              }
                Bedon::where('name','like','%'.'اسماعيل'.'%')->where('nation','ليبيا')->where('family_id',null)
                    ->update(['family_id'=>109]) ;
                Bedon::where('name','like','%'.'عزوز'.'%')->where('nation','ليبيا')->where('family_id',null)
                    ->update(['family_id'=>40]) ;
                Bedon::where('name','like','%'.'غفير'.'%')->where('nation','ليبيا')->where('family_id',null)
                    ->update(['family_id'=>132]) ;
                Bedon::where('name','like','%'.'بدر'.'%')->where('nation','ليبيا')->where('family_id',null)
                    ->update(['family_id'=>51]) ;
                Bedon::where('name','like','%'.'رافع'.'%')->where('nation','ليبيا')->where('family_id',null)
                    ->update(['family_id'=>184]) ;
                Bedon::where('name','like','%'.'نو'.'%')->where('nation','ليبيا')->where('family_id',null)
                    ->update(['family_id'=>130]) ;


                Bedon::where('family_id',null)
                    ->where('nation','ليبيا')
                    ->update(['family_id'=>45]) ;
            }),
            Actions\Action::make('Comparebedon')
                ->label('مقارنة')
                ->icon('heroicon-m-users')
                ->color('info')
                ->url('bedons/comparebedon'),
        ];
    }
}
