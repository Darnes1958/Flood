<?php

namespace App\Filament\Resources\RoadResource\Pages;

use App\Filament\Resources\RoadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoads extends ListRecords
{
    protected static string $resource = RoadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
