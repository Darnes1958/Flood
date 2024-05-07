<?php

namespace App\Filament\Resources\MafkodenResource\Pages;

use App\Filament\Resources\MafkodenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMafkoden extends EditRecord
{
    protected static string $resource = MafkodenResource::class;
    protected ?string $heading='تعديل بيانات مفقود';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
