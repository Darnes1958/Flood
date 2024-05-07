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
            Actions\CreateAction::make(),
          Actions\Action::make('Modifymafkoden')

            ->label('تعديل ')
            ->icon('heroicon-m-users')
            ->color('danger')
            ->url('bedons/modifybedon'),
          Actions\Action::make('setfamily')
            ->label('set family')
            ->visible(false)
            ->action(function (){
              $fam=Family::all();
              foreach ($fam as $item){
                Bedon::
                where('name','like','%'.$item->FamName.'%')
                  ->where('nation','ليبيا')
                  ->update(['family_id'=>$item->id]) ;
                Bedon::
                where('family_id',null)
                  ->where('nation','ليبيا')
                  ->update(['family_id'=>45]) ;

              }
            })
        ];
    }
}
