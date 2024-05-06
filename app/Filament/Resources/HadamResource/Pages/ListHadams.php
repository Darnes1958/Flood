<?php

namespace App\Filament\Resources\HadamResource\Pages;

use App\Filament\Resources\HadamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHadams extends ListRecords
{
    protected static string $resource = HadamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
