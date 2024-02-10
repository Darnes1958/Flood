<?php

namespace App\Filament\Resources\VictimResource\Pages;

use App\Filament\Resources\VictimResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVictims extends ListRecords
{
    protected static string $resource = VictimResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('إضافة ضحية جديدة'),
          Actions\Action::make('byfammily')
          ->label('ادحال يالعائلات')
          ->icon('heroicon-m-users')
            ->color('success')
          ->url('victims/createbyfather')
        ];
    }
}
