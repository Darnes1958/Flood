<?php

namespace App\Filament\User\Resources\VideoResource\Pages;

use App\Filament\User\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVideo extends CreateRecord
{
    protected static string $resource = VideoResource::class;
}