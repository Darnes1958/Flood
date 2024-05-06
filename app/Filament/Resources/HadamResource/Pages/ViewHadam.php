<?php

namespace App\Filament\Resources\HadamResource\Pages;

use App\Filament\Resources\HadamResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewHadam extends ViewRecord
{
    protected static string $resource = HadamResource::class;
    protected ?string $heading='';

    public  function  infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('Wakeel.name'),
                TextEntry::make('damages'),
            ])

            ->columns(3);
    }
}
