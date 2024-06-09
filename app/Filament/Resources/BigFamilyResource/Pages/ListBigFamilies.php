<?php

namespace App\Filament\Resources\BigFamilyResource\Pages;

use App\Filament\Resources\BigFamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBigFamilies extends ListRecords
{
    protected static string $resource = BigFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
