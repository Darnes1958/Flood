<?php

namespace App\Filament\Resources\HadamResource\Pages;

use App\Filament\Resources\HadamResource;
use Filament\Actions;
use Filament\Actions\StaticAction;
use Filament\Resources\Pages\ListRecords;

class ListHadams extends ListRecords
{
    protected static string $resource = HadamResource::class;
    protected ?string $heading=' ';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('اضافة محضر هدم'),
            Actions\Action::make('احصائية')
              ->modalSubmitAction(false)
              ->color('success')
              ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة')
                                                                      ->icon('heroicon-o-arrow-uturn-left')
                                                                      ->color('success'))
                ->modalContent(fn($record) => view("filament.pages.views.hadam-widget", [
                    "record" => $record
                ]))
                ->label("احصائية"),
        ];
    }
}
