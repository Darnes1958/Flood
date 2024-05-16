<?php

namespace App\Filament\Resources\BaitResource\Pages;

use App\Filament\Resources\BaitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBaits extends ListRecords
{
    protected static string $resource = BaitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
