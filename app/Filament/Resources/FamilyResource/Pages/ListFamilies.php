<?php

namespace App\Filament\Resources\FamilyResource\Pages;

use App\Filament\Resources\FamilyResource;
use App\Models\BigFamily;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListFamilies extends ListRecords
{
  protected static string $resource = FamilyResource::class;
  protected ?string $heading="اسماء العائلات";



    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
          Actions\Action::make('Modify')

            ->label('تعديلات ')
            ->icon('heroicon-m-users')
            ->color('danger')
            ->url('families/modifyfamily'),
        ];
    }
}
