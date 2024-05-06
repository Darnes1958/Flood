<?php

namespace App\Filament\Resources\BedonResource\Pages;

use App\Filament\Resources\BedonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBedon extends EditRecord
{
    protected static string $resource = BedonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
