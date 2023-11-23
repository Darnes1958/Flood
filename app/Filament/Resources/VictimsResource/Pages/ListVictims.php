<?php

namespace App\Filament\Resources\VictimsResource\Pages;

use App\Filament\Resources\VictimsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVictims extends ListRecords
{
    protected static string $resource = VictimsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
