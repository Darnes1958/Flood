<?php

namespace App\Filament\Resources\FamilyshowResource\Pages;

use App\Filament\Resources\FamilyshowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFamilyshows extends ListRecords
{
    protected static string $resource = FamilyshowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
