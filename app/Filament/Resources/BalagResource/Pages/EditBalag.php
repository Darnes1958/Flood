<?php

namespace App\Filament\Resources\BalagResource\Pages;

use App\Filament\Resources\BalagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBalag extends EditRecord
{
    protected static string $resource = BalagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
