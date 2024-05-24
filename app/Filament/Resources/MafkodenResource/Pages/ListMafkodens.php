<?php

namespace App\Filament\Resources\MafkodenResource\Pages;

use App\Filament\Resources\MafkodenResource;
use App\Models\Family;
use App\Models\Mafkoden;
use App\Models\Victim;
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
          Actions\Action::make('insert')
           ->visible(false)
            ->label('Insert')
            ->action(function (){
              $res=Mafkoden::where('victim_id',null)
                ->where('repeted',0)
                ->where('nation','!=','ليبيا')
                ->get();
              foreach ($res as $item)
              {
                if ($item->male=='1') $male='ذكر'; else $male='أنثي';
                Victim::create([
                  'Name1'=>$item->Name1,
                  'Name2'=>$item->Name2,
                  'Name3'=>$item->Name3,
                  'Name4'=>$item->Name4,
                  'FullName'=>$item->name,
                  'family_id'=>$item->family_id,
                  'street_id'=>12,
                  'male'=>$male,
                  'notes'=>$item->notes,
                  'mafkoden'=>$item->id,
                  'fromwho'=>'مفقودين',
                ]);
              }
            }),
          Actions\Action::make('Naming')
            ->visible(false)
            ->label('set Name\'S')
            ->action(function (){
              $res=Mafkoden::all();
              foreach ($res as $item)
              {
                $name=$item->name;
                $array = str_split($name);
                $name1='';$name2='';$name3='';$name4='';
                $begin1=true;$begin2=false;$begin3=false;$begin4=false;

                foreach($array as $val){
                  if ($begin1) {
                    if ($name1=='عبد' || $name1=='بن' || $name1=='ابو' || $name1=='ام' || $val!=' ')
                      $name1=$name1.$val;
                    else
                    {$begin1=false;$begin2=true;continue;}
                  }

                  if ($begin2) {
                    if ($name2=='عبد' || $name2=='بن' || $name2=='ابو' || $name2=='ام' || $val!=' ' )
                      $name2=$name2.$val;
                    else {
                      if ($name2=='الله') {$name1=$name1.$val.$name2;$name2='';}
                      else {$begin2=false;$begin3=true;continue;}
                    }
                  }
                  if ($begin3) {
                    if ($name3=='عبد' || $name3=='بن' || $name3=='ابو' || $name3=='ام' || $val!=' ')
                      $name3=$name3.$val;
                    else
                    {
                      if ($name3=='الله') {$name2=$name2.$val.$name3;$name3='';}
                      else {$begin3=false;$begin4=true;continue;}
                    }
                  }
                  if ($begin4) {
                    if ($name4=='الله') {$name3=$name3.' '.$name4;$name4='';}
                    $name4=$name4.$val;}


                }

                $item->Name1=$name1;$item->Name2=$name2;$item->Name3=$name3;$item->Name4=$name4;
                $item->save();
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
