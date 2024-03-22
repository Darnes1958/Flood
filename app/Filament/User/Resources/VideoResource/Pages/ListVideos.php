<?php

namespace App\Filament\User\Resources\VideoResource\Pages;

use App\Filament\User\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVideos extends ListRecords
{
    protected static string $resource = VideoResource::class;
    protected ?string $heading='';

}
