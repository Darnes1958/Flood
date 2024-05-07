<?php

namespace App\Filament\Resources\TasreehResource\Pages;

use App\Filament\Resources\TasreehResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTasreeh extends EditRecord
{
    protected static string $resource = TasreehResource::class;
    protected ?string $heading='تعديل بيانات بتصريح';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
