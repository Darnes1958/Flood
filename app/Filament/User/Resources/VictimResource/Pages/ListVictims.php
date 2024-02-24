<?php

namespace App\Filament\User\Resources\VictimResource\Pages;

use App\Filament\User\Resources\VictimResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVictims extends ListRecords
{
    protected static string $resource = VictimResource::class;
protected ?string $heading="";

}
