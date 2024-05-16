<?php

namespace App\Filament\Resources\BaitResource\Pages;

use App\Filament\Resources\BaitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBait extends EditRecord
{
    protected static string $resource = BaitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
