<?php

namespace App\Filament\User\Resources\VictimResource\Pages;

use App\Filament\User\Resources\VictimResource;
use App\Models\Victim;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListVictims extends ListRecords
{
    protected static string $resource = VictimResource::class;
    public $family_id=1;
  protected function getHeaderActions(): array
  {
    return [
      Action::make('Prepere')
       ->action('Do'),
     Action::make('print')
       ->url(fn (): string => route('pdffamily', ['family_id' => $this->family_id])),
    ];
  }
  public function Do(){

    $this->filters=$this->table->getFilters();
    $this->family_id=$this->filters['family']->getState()['value'];

  }


}
