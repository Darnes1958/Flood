<?php

namespace App\Filament\Resources\HadamResource\Pages;

use App\Filament\Resources\HadamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHadam extends EditRecord
{
    protected static string $resource = HadamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
