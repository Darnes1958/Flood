<?php

namespace App\Filament\Resources\FamilyshowResource\Pages;

use App\Filament\Resources\FamilyshowResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFamilyshow extends EditRecord
{
    protected static string $resource = FamilyshowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
