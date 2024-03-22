<?php

namespace App\Filament\User\Resources\VictimResource\Pages;

use App\Filament\User\Resources\VictimResource;
use App\Models\Victim;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListVictims extends ListRecords
{
    protected static string $resource = VictimResource::class;
    public $family_id;
    protected ?string $heading=' ';
  protected function getHeaderActions(): array
  {
    return [
     Action::make('طباعة')
       ->icon('heroicon-m-printer')
       ->url(function () {
         $this->filters=$this->table->getFilters();
         $this->family_id=$this->filters['family']->getState()['value'];
         if (!$this->family_id) {

           return false;
         }

         return route('pdffamily', ['family_id' => $this->family_id]);
       } ),
    ];
  }


}
