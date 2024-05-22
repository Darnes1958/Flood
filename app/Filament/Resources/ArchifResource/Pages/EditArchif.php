<?php

namespace App\Filament\Resources\ArchifResource\Pages;

use App\Filament\Resources\ArchifResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArchif extends EditRecord
{
    protected static string $resource = ArchifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
