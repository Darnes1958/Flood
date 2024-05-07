<?php

namespace App\Filament\Resources\MafkodenResource\Pages;

use App\Filament\Resources\MafkodenResource;
use App\Models\Family;
use App\Models\Mafkoden;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMafkodens extends ListRecords
{
    protected static string $resource = MafkodenResource::class;
protected ?string $heading='مفقودين';
    protected function getHeaderActions(): array
    {
        return [
          Actions\CreateAction::make()
            ->label('إضافة'),
          Actions\Action::make('Modifymafkoden')
            ->label('تعديلات')
            ->icon('heroicon-m-users')
            ->color('danger')
            ->url('mafkodens/modifymafkoden'),
          Actions\Action::make('setfamily')
           ->visible(false)
           ->label('set family')
           ->action(function (){
             $fam=Family::all();
             foreach ($fam as $item){
               Mafkoden::
                 where('name','like','%'.$item->FamName.'%')
                 ->where('nation','ليبيا')
                 ->update(['family_id'=>$item->id]) ;
               Mafkoden::
               where('family_id',null)
                 ->where('nation','ليبيا')
                 ->update(['family_id'=>45]) ;

             }
           }),
          Actions\Action::make('Comparemafkoden')
            ->label('مقارنة')
            ->icon('heroicon-m-users')
            ->color('info')
            ->url('mafkodens/comparemafkoden'),
        ];
    }
}
