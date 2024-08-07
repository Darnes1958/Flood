<?php

namespace App\Filament\Resources\DeadResource\Pages;

use App\Filament\Resources\DeadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDead extends EditRecord
{
    protected static string $resource = DeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
