<?php

namespace App\Filament\Resources\TasreehResource\Pages;

use App\Filament\Resources\TasreehResource;
use App\Models\Bedon;
use App\Models\Family;
use App\Models\Tasreeh;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTasreehs extends ListRecords
{
    protected static string $resource = TasreehResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
             ->label('إضافة'),
            Actions\Action::make('Modifytasreeh')

                ->label('تعديلات ')
                ->icon('heroicon-m-users')
                ->color('danger')
                ->url('tasreehs/modifytasreeh'),
            Actions\Action::make('setfamily')
                ->label('set family')
                ->visible(false)
                ->action(function (){
                    $fam=Family::all();
                    foreach ($fam as $item){
                        Tasreeh::
                        where('name','like','%'.$item->FamName.'%')
                            ->where('nation','ليبيا')
                            ->update(['family_id'=>$item->id]) ;
                        Tasreeh::
                        where('family_id',null)
                            ->where('nation','ليبيا')
                            ->update(['family_id'=>45]) ;

                    }
                }),
            Actions\Action::make('Comparetasreeh')
                ->label('مقارنة')
                ->icon('heroicon-m-users')
                ->color('info')
                ->url('tasreehs/comparetasreeh'),
        ];
    }
}
