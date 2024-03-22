<?php

namespace App\Filament\User\Resources\VideoResource\Pages;

use App\Filament\User\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVideo extends ViewRecord
{
    protected static string $resource = VideoResource::class;
    protected ?string $heading='';

  protected static string $view = 'filament.pages.view-video';

}
