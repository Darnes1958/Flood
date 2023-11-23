<?php

namespace App\Filament\Resources\VictimsResource\Pages;

use App\Filament\Resources\VictimsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVictims extends EditRecord
{
    protected static string $resource = VictimsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
